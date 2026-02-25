"""
Web Scraper Script - Markdown Output
=====================================
A Python script to scrape websites and extract content as properly formatted Markdown.

Requirements:
    pip install requests beautifulsoup4 lxml

Usage:
    python web_scraper.py <url> --output output.md
    python web_scraper.py <url> --output output.md --depth 2
"""

import argparse
import re
import time
from urllib.parse import urljoin, urlparse
from typing import Dict, List, Set, Optional, Any
import os

try:
    import requests
    from bs4 import BeautifulSoup, NavigableString, Tag
except ImportError:
    print("Error: Required packages not installed.")
    print("Please run: pip install requests beautifulsoup4 lxml")
    exit(1)


class WebScraper:
    """
    A web scraper that extracts content from websites and outputs formatted Markdown.
    """
    
    def __init__(
        self,
        base_url: str,
        max_depth: int = 0,
        delay: float = 1.0,
        timeout: int = 30,
        user_agent: str = None
    ):
        self.base_url = base_url
        self.max_depth = max_depth
        self.delay = delay
        self.timeout = timeout
        self.visited_urls: Set[str] = set()
        self.results: List[str] = []
        
        self.headers = {
            'User-Agent': user_agent or 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language': 'en-US,en;q=0.5',
        }
        
        parsed = urlparse(base_url)
        self.base_domain = parsed.netloc
    
    def is_valid_url(self, url: str) -> bool:
        try:
            parsed = urlparse(url)
            return bool(parsed.netloc) and parsed.netloc == self.base_domain
        except Exception:
            return False
    
    def normalize_url(self, url: str) -> str:
        url = urljoin(self.base_url, url)
        parsed = urlparse(url)
        clean_url = f"{parsed.scheme}://{parsed.netloc}{parsed.path}"
        if parsed.query:
            clean_url += f"?{parsed.query}"
        return clean_url.rstrip('/')
    
    def fetch_page(self, url: str) -> Optional[str]:
        try:
            print(f"Fetching: {url}")
            response = requests.get(
                url,
                headers=self.headers,
                timeout=self.timeout,
                allow_redirects=True
            )
            response.raise_for_status()
            return response.text
        except requests.exceptions.RequestException as e:
            print(f"Error fetching {url}: {e}")
            return None
    
    def find_content_container(self, soup: BeautifulSoup) -> Tag:
        """Find the main content container in the page."""
        selectors = [
            ('main', {}),
            ('article', {}),
            ('div', {'class': re.compile(r'content__default|content|main-content|documentation|docs-content', re.I)}),
            ('div', {'id': re.compile(r'content|main|documentation', re.I)}),
            ('div', {'class': re.compile(r'markdown|prose|post', re.I)}),
        ]
        
        for tag, attrs in selectors:
            element = soup.find(tag, attrs)
            if element:
                if tag == 'main':
                    content_div = element.find('div', class_=re.compile(r'content', re.I))
                    if content_div:
                        return content_div
                return element
        
        return soup.body if soup.body else soup
    
    def get_code_language(self, element: Tag) -> str:
        """Extract language from code element classes."""
        classes = element.get('class', [])
        if isinstance(classes, str):
            classes = classes.split()
        
        for cls in classes:
            if cls.startswith('language-'):
                return cls.replace('language-', '')
            elif cls.startswith('lang-'):
                return cls.replace('lang-', '')
        
        return 'php'  # Default to PHP for Laravel docs
    
    def clean_heading_text(self, text: str) -> str:
        """Clean heading text by removing # prefix and extra whitespace."""
        text = re.sub(r'^#+\s*', '', text)
        text = re.sub(r'\s+', ' ', text)
        return text.strip()
    
    def clean_text(self, text: str) -> str:
        """Clean text by normalizing whitespace."""
        text = re.sub(r'\s+', ' ', text)
        return text.strip()
    
    def process_inline_elements(self, element: Tag, url: str) -> str:
        """Process inline elements like links, bold, italic, code."""
        result = []
        
        for child in element.children:
            if isinstance(child, NavigableString):
                result.append(str(child))
            elif child.name == 'code':
                result.append(f"`{child.get_text()}`")
            elif child.name == 'a':
                href = urljoin(url, child.get('href', ''))
                text = child.get_text()
                result.append(f"[{text}]({href})")
            elif child.name in ['strong', 'b']:
                result.append(f"**{child.get_text()}**")
            elif child.name in ['em', 'i']:
                result.append(f"*{child.get_text()}*")
            else:
                result.append(child.get_text())
        
        return ''.join(result)
    
    def table_to_markdown(self, table: Tag) -> str:
        """Convert HTML table to Markdown."""
        rows = []
        headers = []
        
        thead = table.find('thead')
        if thead:
            for th in thead.find_all('th'):
                headers.append(th.get_text(strip=True))
        
        tbody = table.find('tbody') or table
        for tr in tbody.find_all('tr'):
            cells = []
            for cell in tr.find_all(['td', 'th']):
                cells.append(cell.get_text(strip=True))
            if cells:
                rows.append(cells)
        
        if not headers and rows:
            headers = rows[0]
            rows = rows[1:]
        
        if not headers:
            return ''
        
        md = "\n| " + " | ".join(headers) + " |\n"
        md += "| " + " | ".join(["---"] * len(headers)) + " |\n"
        for row in rows:
            while len(row) < len(headers):
                row.append('')
            md += "| " + " | ".join(row[:len(headers)]) + " |\n"
        
        return md
    
    def extract_markdown(self, html: str, url: str) -> str:
        """Extract content from HTML and convert to Markdown."""
        soup = BeautifulSoup(html, 'lxml')
        
        # Remove unwanted elements
        for elem in soup(['script', 'style', 'nav', 'footer', 'header', 'aside']):
            elem.decompose()
        
        # Get metadata
        title = soup.find('title')
        title_text = title.get_text(strip=True) if title else ''
        
        meta_desc = soup.find('meta', attrs={'name': 'description'})
        description = meta_desc.get('content', '') if meta_desc else ''
        
        # Find content container
        content = self.find_content_container(soup)
        
        # Build markdown
        md_parts = []
        md_parts.append(f"# {title_text}\n")
        md_parts.append(f"\n**URL:** {url}\n")
        if description:
            md_parts.append(f"\n**Description:** {description}\n")
        md_parts.append("\n---\n")
        
        # Track processed elements
        processed = set()
        
        # Get all relevant elements in document order
        elements = content.find_all(['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'pre', 'ul', 'ol', 'table', 'blockquote', 'img', 'svg'], recursive=True)
        
        for element in elements:
            # Skip if already processed
            if id(element) in processed:
                continue
            
            # Skip elements inside pre or code
            if element.find_parent('pre') or element.find_parent('code'):
                continue
            
            processed.add(id(element))
            
            # Handle headings
            if element.name in ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']:
                level = int(element.name[1])
                text = self.clean_heading_text(element.get_text())
                md_parts.append(f"\n{'#' * level} {text}\n")
            
            # Handle paragraphs
            elif element.name == 'p':
                text = self.process_inline_elements(element, url)
                text = self.clean_text(text)
                if text:
                    md_parts.append(f"\n{text}\n")
            
            # Handle code blocks
            elif element.name == 'pre':
                code = element.find('code')
                if code:
                    lang = self.get_code_language(code)
                    code_text = code.get_text()
                else:
                    lang = self.get_code_language(element)
                    code_text = element.get_text()
                
                md_parts.append(f"\n```{lang}\n{code_text}```\n")
            
            # Handle lists
            elif element.name in ['ul', 'ol']:
                ordered = element.name == 'ol'
                items = []
                for i, li in enumerate(element.find_all('li', recursive=False), 1):
                    text = self.clean_text(li.get_text())
                    if ordered:
                        items.append(f"  {i}. {text}")
                    else:
                        items.append(f"  - {text}")
                md_parts.append("\n" + "\n".join(items) + "\n")
            
            # Handle blockquotes
            elif element.name == 'blockquote':
                text = self.clean_text(element.get_text())
                lines = [f"> {line}" for line in text.split('\n') if line.strip()]
                md_parts.append("\n" + "\n".join(lines) + "\n")
            
            # Handle tables
            elif element.name == 'table':
                table_md = self.table_to_markdown(element)
                if table_md:
                    md_parts.append(table_md)
            
            # Handle images
            elif element.name == 'img':
                src = urljoin(url, element.get('src', ''))
                alt = element.get('alt', '')
                md_parts.append(f"\n![{alt}]({src})\n")
            
            # Handle SVG
            elif element.name == 'svg':
                title_elem = element.find('title')
                svg_title = title_elem.get_text() if title_elem else 'Diagram'
                md_parts.append(f"\n*[SVG Diagram: {svg_title}]*\n")
        
        # Join and clean up
        md = ''.join(md_parts)
        
        # Clean up excessive newlines
        md = re.sub(r'\n{4,}', '\n\n\n', md)
        
        return md
    
    def get_internal_links(self, html: str, url: str) -> List[str]:
        """Extract all internal links from a page."""
        soup = BeautifulSoup(html, 'lxml')
        links = []
        
        for a in soup.find_all('a', href=True):
            href = urljoin(url, a['href'])
            normalized = self.normalize_url(href)
            if self.is_valid_url(normalized) and normalized not in self.visited_urls:
                links.append(normalized)
        
        return list(set(links))
    
    def scrape(self, url: str = None, depth: int = 0) -> List[str]:
        """Recursively scrape a website."""
        if url is None:
            url = self.base_url
        
        url = self.normalize_url(url)
        
        if url in self.visited_urls or depth > self.max_depth:
            return self.results
        
        self.visited_urls.add(url)
        
        if len(self.visited_urls) > 1:
            time.sleep(self.delay)
        
        html = self.fetch_page(url)
        if html is None:
            return self.results
        
        md = self.extract_markdown(html, url)
        self.results.append(md)
        
        if depth < self.max_depth:
            internal_links = self.get_internal_links(html, url)
            for link in internal_links:
                self.scrape(link, depth + 1)
        
        return self.results
    
    def save_results(self, output_file: str):
        """Save scraped results to a markdown file."""
        with open(output_file, 'w', encoding='utf-8') as f:
            for i, md in enumerate(self.results):
                f.write(md)
                if i < len(self.results) - 1:
                    f.write("\n\n---\n\n## Next Page\n\n")
        
        print(f"Results saved to: {output_file}")


def main():
    parser = argparse.ArgumentParser(
        description='Web Scraper - Extract content from websites as formatted Markdown',
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog="""
Examples:
    python web_scraper.py https://example.com --output docs.md
    python web_scraper.py https://example.com --output docs.md --depth 2
    python web_scraper.py https://docs.laravel-excel.com/3.1/architecture/ --output laravel_excel.md
        """
    )
    
    parser.add_argument('url', help='URL to scrape')
    parser.add_argument('--output', '-o', default='scraped_content.md',
                        help='Output markdown file path (default: scraped_content.md)')
    parser.add_argument('--depth', '-d', type=int, default=0,
                        help='Maximum depth for recursive scraping (default: 0, single page)')
    parser.add_argument('--delay', type=float, default=1.0,
                        help='Delay between requests in seconds (default: 1.0)')
    parser.add_argument('--timeout', type=int, default=30,
                        help='Request timeout in seconds (default: 30)')
    parser.add_argument('--user-agent', '-ua',
                        help='Custom user agent string')
    
    args = parser.parse_args()
    
    scraper = WebScraper(
        base_url=args.url,
        max_depth=args.depth,
        delay=args.delay,
        timeout=args.timeout,
        user_agent=args.user_agent
    )
    
    print(f"Starting web scraper...")
    print(f"Base URL: {args.url}")
    print(f"Max Depth: {args.depth}")
    print(f"Output: {args.output}")
    print("-" * 50)
    
    results = scraper.scrape()
    
    print("-" * 50)
    print(f"Scraping complete!")
    print(f"Pages scraped: {len(results)}")
    
    scraper.save_results(args.output)


if __name__ == '__main__':
    main()

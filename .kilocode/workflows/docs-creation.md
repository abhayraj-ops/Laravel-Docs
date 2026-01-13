# Laravel Documentation Creation Workflow

# This workflow defines the process for creating and maintaining Laravel documentation

name: Laravel Docs Creation

description: >
  A structured workflow for creating comprehensive Laravel documentation
  with code examples, visualizations, and best practices.

# Workflow Steps

steps:
  
  # Step 1: Research and Planning
  - name: Research
    description: >
      Gather information from official Laravel documentation and community resources
    tasks:
      - Read official Laravel documentation for the topic
      - Review community tutorials and blog posts
      - Identify key concepts and subtopics
      - Note any common pitfalls or best practices
    outputs:
      - List of key concepts to cover
      - Outline of documentation structure
      - References to official documentation
  
  # Step 2: Create Documentation Outline
  - name: Outline Creation
    description: >
      Create a structured outline for the documentation
    tasks:
      - Define main sections and subsections
      - Organize content in logical flow
      - Identify where code examples are needed
      - Plan visualization diagrams
    outputs:
      - Markdown outline with headings
      - List of required code examples
      - List of required diagrams
  
  # Step 3: Write Content
  - name: Content Writing
    description: >
      Write the main documentation content
    tasks:
      - Write clear, concise explanations
      - Include technical definitions
      - Add practical examples
      - Explain concepts with analogies
    outputs:
      - Complete markdown content
      - Technical definitions section
      - Practical examples
  
  # Step 4: Add Code Examples
  - name: Code Examples
    description: >
      Create relevant code examples with proper formatting
    tasks:
      - Write working code examples
      - Include file paths and locations
      - Add comments for clarity
      - Ensure code follows Laravel best practices
    outputs:
      - Code blocks with syntax highlighting
      - File references for each example
      - Comments explaining key parts
  
  # Step 5: Create Visualizations
  - name: Visualizations
    description: >
      Create Mermaid diagrams and other visual aids
    tasks:
      - Design flow diagrams
      - Create sequence diagrams
      - Add class diagrams where helpful
      - Include state diagrams for complex flows
    outputs:
      - Mermaid code blocks
      - Visual representations of concepts
      - Diagrams showing data flow
  
  # Step 6: Add Best Practices
  - name: Best Practices
    description: >
      Include best practices and common pitfalls
    tasks:
      - List recommended approaches
      - Warn about common mistakes
      - Provide performance tips
      - Include security considerations
    outputs:
      - Best practices section
      - Common pitfalls warnings
      - Performance optimization tips
  
  # Step 7: Review and Testing
  - name: Review
    description: >
      Review and test the documentation
    tasks:
      - Verify all code examples work
      - Check for grammatical errors
      - Ensure consistent formatting
      - Validate all links and references
    outputs:
      - Error-free documentation
      - Working code examples
      - Valid references
  
  # Step 8: Finalize and Publish
  - name: Finalization
    description: >
      Finalize and prepare for publication
    tasks:
      - Add table of contents
      - Include metadata (title, description)
      - Add cross-references to related docs
      - Prepare for version control
    outputs:
      - Complete, publish-ready documentation
      - Properly formatted markdown file
      - Ready for version control
      - Commit and push to github.

# Documentation Structure Template

template:
  - Introduction
    - Brief overview of the topic
    - Why it's important
    - What will be covered
  
  - Technical Definition
    - Clear definition of the concept
    - Key terms and terminology
    - How it fits into Laravel ecosystem
  
  - Visualization
    - Mermaid diagram showing concept
    - Flow diagrams
    - Sequence diagrams
  
  - Code Examples
    - Working code snippets
    - File references
    - Explanations of key parts
  
  - Best Practices
    - Recommended approaches
    - Common mistakes to avoid
    - Performance considerations
  
  - Conclusion
    - Summary of key points
    - Next steps
    - Related documentation

# Quality Standards

standards:
  - Code examples must be tested and working
  - All technical terms must be clearly defined
  - Visualizations should enhance understanding
  - Documentation should be comprehensive yet concise
  - Follow consistent formatting and style
  - Include proper file references for all examples
  - Maintain up-to-date with current Laravel version

# Tools and Resources

resources:
  - Official Laravel Documentation
  - Laravel Source Code
  - Community Tutorials
  - Stack Overflow
  - GitHub Issues and Discussions
  - Laravel News
  - Laravel Podcast

# Version Control

version_control:
  - Use semantic versioning for documentation
  - Track changes in git
  - Include changelog for major updates
  - Tag releases appropriately
  - Maintain backward compatibility where possible
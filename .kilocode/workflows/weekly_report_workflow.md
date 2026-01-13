# Weekly Report Workflow

## Overview
This workflow automates the creation of weekly reports for the BOINT-24 task. The report is generated every Monday at 1:30 PM, coinciding with the weekly backend meeting.

## Workflow Steps

### 1. Gather Data
- Collect all tasks completed during the week from the daily reports.
- Review the progress made on the BOINT-24 task.
- Identify any problems encountered and their resolutions.

### 2. Create Weekly Report
- Generate a markdown file in the `BOINT-24/weekly` folder.
- Include the following sections:
  - **Overview**: Brief summary of the week's progress.
  - **Tasks Completed**: Detailed list of tasks completed each day.
  - **Problems Encountered**: Any issues faced and their resolutions.
  - **Tasks for Next Week**: Planned tasks for the upcoming week.
  - **Summary**: Overall summary of the week's achievements.

### 3. Save Report
- Save the report in the `BOINT-24/weekly` folder with the naming convention `weekly_report_<date>.md`.
- Ensure the report is ready for review by the client.

### 4. Notify Team
- Share the report with the team during the weekly backend meeting.
- Discuss any challenges and plan for the upcoming week.

## Example Report Structure

```markdown
# Weekly Report - <Date>

## Overview
This report summarizes the progress made on the BOINT-24 task during the past week.

## Tasks Completed

### <Day 1>
- Task 1
- Task 2

**Problems Encountered**:
- Problem 1
- Problem 2

### <Day 2>
- Task 1
- Task 2

**Problems Encountered**:
- None reported.

## Tasks for Next Week
- Task 1
- Task 2

## Summary
Summary of the week's achievements and next steps.

## Reported By
<Your Name>

## Date
<Date>
```

## Automation
To automate this workflow, you can use a script or a task scheduler to:
1. Collect data from daily reports.
2. Generate the weekly report markdown file.
3. Save the report in the specified folder.
4. Notify the team via email or a messaging platform.

## Notes
- Ensure all daily reports are up-to-date before generating the weekly report.
- Review the report for accuracy and completeness before sharing it with the team.

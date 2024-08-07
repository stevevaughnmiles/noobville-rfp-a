<?php
require '../../vendor/autoload.php';
require '../../vendor/erusev/parsedown/Parsedown.php';

$parsedown = new Parsedown();

$rfpMarkdown = <<<MD
# Request for Proposal (RFP C - Calendar)
## Noobville Guild of Travelers
**Project: Enhanced Event Calendar Integration**

### Introduction
Greetings, adventurers! The Noobville Guild of Travelers seeks the expertise of skilled digital wizards and web architects to enhance the presentation of our town’s events within a new and improved calendar system on our website. Our goal is to create a captivating and user-friendly event calendar that drives engagement and increases attendance at our local events.


### Project Scope
- **Event Calendar Enhancement**: Develop a strategy to improve the integration and presentation of event information within a new calendar system.
- **Interactive and Dynamic Calendar Features**: Create interactive elements within the calendar that make event information more accessible and engaging.

### Deliverables (Presentation 10 min, Q&A 5 min)
- **Approach Summary**: A brief overview of your guild’s approach to enhancing the event calendar.
- **Proof of Concept (POC)**: A functional prototype demonstrating the new calendar features.
- **Estimate of Hours**: A detailed estimate of the hours required to complete this quest.
- **Tasks and Concerns Outline**: Identify the key tasks and potential obstacles associated with this project.

### Timing of Event
- **Support**: You can ask up to 3 questions, but each question and answers will be shared with everyone.
- **Key People**:

| Role | Name |
| ---------  | -------------- |
| MIA | Karin Mast |
| CMO | Angela Vaughn |
| CTO | Gray Lawry |
| CXO | CA Clark |
| Help Desk | Justin Huot |

### Where to find information you care about.
go to http://dallas-dev.lndo.site/calendar.php

MD;

$rfpHtml = $parsedown->text($rfpMarkdown);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFP C: Enhanced Event Calendar Integration</title>
    <link rel="stylesheet" href="/css/internal.css">
</head>
<body>
    <div class="rfp-content">
        <?= $rfpHtml ?>
    </div>
</body>
</html>

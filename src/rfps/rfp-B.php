<?php
require '../../vendor/autoload.php';
require '../../vendor/erusev/parsedown/Parsedown.php';

$parsedown = new Parsedown();

$rfpMarkdown = <<<MD
# Request for Proposal (RFP B - Listings Grid)
## Noobville Guild of Travelers
**Project: Website Refresh and Listings Grid Reimagining**

### Introduction
Greetings, adventurers! The Noobville Guild of Travelers seeks the expertise of skilled digital wizards and web architects to embark on a quest to refresh our town’s website. Our ultimate goal is to attract more travelers and increase our town's revenue. A key part of this quest is to reimagine our Listings Grid to make it more engaging and user-friendly.

### Project Scope
- **Website Refresh**: Enchant the Noobville website with a fresh and modern design, enhancing the user experience and navigation.
- **Listings Grid Reimagining**: Create a Proof of Concept (POC) for an innovative Listings Grid that captivates visitors and encourages them to explore Noobville.

### Deliverables (Presentation 10 min, Q&A 5 min)
- **Approach Summary**: A brief overview of your guild’s approach to refreshing the website and reimagining the Listings Grid.
- **Proof of Concept (POC)**: A functional prototype of the reimagined Listings Grid.
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
go to http://dallas-dev.lndo.site/search.php

MD;

$rfpHtml = $parsedown->text($rfpMarkdown);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFP B: Website Refresh and Listings Grid Reimagining</title>
    <link rel="stylesheet" href="/css/internal.css">
</head>
<body>
    <div class="rfp-content">
        <?= $rfpHtml ?>
    </div>
</body>
</html>

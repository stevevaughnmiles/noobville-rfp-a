<?php
require '../../vendor/autoload.php';
require '../../vendor/erusev/parsedown/Parsedown.php';

$parsedown = new Parsedown();

$rfpMarkdown = <<<MD
# Request for Proposal (RFP A - Articles)
## Noobville Guild of Travelers
**Project: Website Integration of Listings Information in Articles**

### Introduction
Greetings, adventurers! The Noobville Guild of Travelers seeks the expertise of skilled digital wizards and web architects to enhance the integration of our town’s listings information within the articles on our website. Our goal is to seamlessly embed listings to create a more engaging and informative experience for visitors, ultimately driving more interest and increasing revenue.

### Project Scope
- **Website Integration Enhancement**: Develop a strategy to effectively integrate listings information into articles, enhancing user engagement and providing more value to our visitors.
- **Interactive and Dynamic Content**: Create interactive elements within articles that showcase listings in a captivating manner.

### Deliverables (Presentation 10 min, Q&A 5 min)
- **Approach Summary**: A brief overview of your guild’s approach to integrating listings information within articles.
- **Proof of Concept (POC)**: A functional prototype demonstrating the integration of listings within an article.
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
Articles can be found by going to the search page (http://dallas-dev.lndo.site/search.php), filtering for Articles and clicking link (http://dallas-dev.lndo.site/view_article.php?id=)
MD;

$rfpHtml = $parsedown->text($rfpMarkdown);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFP A: Website Integration of Listings Information in Articles</title>
    <link rel="stylesheet" href="/css/internal.css">
</head>
<body>
    <div class="rfp-content">
        <?= $rfpHtml ?>
    </div>
</body>
</html>

# History

Assessment questions were once embeded in a large component called Test and Assessment. The Test Question Pool object and the Test object of ILIAS were not strictly separated and the assessment question integration was done within both components. This lead to a strong depency between the Test and the Test Question Pool object in the past. The codebase for the two modules was fully mixed up with the code for the questions.

Today, this failure in architecture got fixed by fully separating the components and by extracting a new service AssessmentQuestion.

Furthermore, the database has been decoupled as the supposed separation in two different table spaces within the former Test and Assessment component did not reflect a neccessary strict distinction. All information in the database about the assessment questions were migrated to the new table space of the AssessmentQuestion service.

# Introduction

This documentation describes the interfaces the AssessmentQuestion service comes with and how they are to be used by developers who want to integrate assessment questions to their components.

The AssessmentQuestion service is designed as a component that offers complex functionality for consumers. The way other components can integrate assessment questions keeps it as flexible as possible. The higher level business logic is handled by the consumer. E.g. the business logic that a question can only be answered once or the business logic for handling a group of questions such as that a question can only be answered once. The lower level business logic around assessment questions with a focus on a single question is covered in the Assessment Question Service. E.g. the arrangement of points for answer options.

# Usage
The Assessment Question API provides the following Services:

- Assessment Factory
- Question Authoring
- Question Service
- Answer Service

When integrating questions to any component for authoring purposes, a ctrlCalls to class.ilAsqQuestionAuthoringGui.php has to be implemented and as well as a forwarding in the consumer's `executeCommand()` method.

The consuming component is also responsible for checking the RBAC Permissions. 

Additionally the consuming component has an opportunity to provide any command link either as a button (like the well known check button) rendered within the question canvas or as an entry in an question actions menu (e.g. discard or postpone solution).

# Public Services

## AuthoringQuestion
This service offers a wide range of link generation tools.
### Get question creation link
The authoring question service offers a creation form for questions. You can get the link to this form as follows:
```
AuthoringQuestion::getCreationLink([])
```
Please note that the ILIAS Ctrl-Flow will pass through your current GUI Class! And you are responsible for checking the permissions for this action!
### Get question edit link
Additionally, the service offers an edit link to a specific question:
```
AuthoringQuestion::getEditLink($question_id)
```
Please note that the ILIAS Ctrl-Flow will pass through your current GUI Class! And you are responsible for checking the permissions for this action!
Apart from these two main use cases, the service also offers:
- getPreviewLink()
- getEditPageLink()
- getEditFeedbacksLink()
- getEditHintsLink()

### Set question UID
TODO
## AssessmentFactory
This service provides handy singleton instances for the question service and answer service.

```
$assessment_factory = new AssessmentFactory();

// Get question service
$question_service = $assessment_factory->question();
// Get answer service
$answer_service = $assessment_factory->answer();
```
## QuestionService
Use this service to manage questions.
### Get Questions
There are several ways to fetch questions. Currently, it's possible to fetch questions with either their UUID or with a container id.
#### Fetch with UUID
```
$question = $question_service->getQuestionByQuestionId($id)
```
#### Fetch with container ID
```
$questions = $question_service->getQuestionsOfContainer($container_id)
```
### Create Questions
```
$question = $question_service->createQuestion($type, $container_id, $content_editing_mode);
```
### Update Questions
```
$question_service->saveQuestion($question);
```
### Delete Questions
Not yet implemented.
##AnswerService
This service offers means to receive answer-specific data from questions.
```
$answer_service = new AnswerService()
```
### Get score
```
$score = $answer_service->getScore($question, $answer)
```
### Get max score
```
$max_score = $answer_service->getMaxScore($question)
```
### Get best answer
```
$best_answer = $answer_service->getBestAnswer($question)
```

# Example Consumers (Test/Pool/LearningModule)

[Services/AssessmentQuestion/examples/class.exObjQuestionPoolGUI.php](../examples/class.exObjQuestionPoolGUI.php)

[Services/AssessmentQuestion/examples/class.exQuestionsTableGUI.php](../examples/class.exQuestionsTableGUI.php)

[Services/AssessmentQuestion/examples/class.exTestPlayerGUI.php](../examples/class.exTestPlayerGUI.php)

[Services/AssessmentQuestion/examples/class.exPageContentQuestions.php](../examples/class.exPageContentQuestions.php)

[Services/AssessmentQuestion/examples/class.exQuestionPoolExporter.php](../examples/class.exQuestionPoolExporter.php)

[Services/AssessmentQuestion/examples/class.exQuestionPoolImporter.php](../examples/class.exQuestionPoolImporter.php)


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

The consuming component is responsible for checking the RBAC Permissions. 

Please also note that assessment questions are accessed with a UUID.

# Public Services

## AuthoringQuestion
This service offers a wide range of link generation tools.
### Implementation
1. The first step is to choose one of the existing link generation methods of this service and either redirect or hook the generated link to a GUI component.
```
// Generate a link using a method of this service
$creationLink = AuthoringQuestion::getCreationLink([]);

// Apply the generated link onto a GUI component
self::dic()->toolbar()->addButton($creationLink->getLabel(), $creationLink->getAction());
```
2. The next step is to add a ilCtrl_Calls from your component to ilAsqQuestionAuthoringGUI. Make sure to reload the control structure, if necessary.
```
@ilCtrl_Calls      ilObjYourGUI: ilAsqQuestionAuthoringGUI
```
3. Catch any redirects to the `ilAsqQuestionAuthoringGUI` class within the `executeCommand()` or `performCommand()` methods, depending on whether you're working with a plugin or not. Consult step 5 on how to properly implement a forwarding mechanism tailored to this service.

4. It is highly advised to verify the permissions of users trying to access link generation methods.
```
switch (strtolower($next_class)) {
            case strtolower(ilAsqQuestionAuthoringGUI::class):
                if (!x::hasWriteAccess()) {
                    // Handle permission mismatch;
            	}
                ...
}
```

An AuthoringContextContainer object is required to hold this metadata. The following parameters are required:
```
UiStandardLink $backLink,
int $refId,
int $objId,
string $objType,
int $actorId,
bool $writeAccess,
```
The context has to be passed on to an `ilAsqQuestionAuthoringGUI` object. The last step is to forward the `ilAsqQuestionAuthoringGUI` object. An implementation of this functionality may look like this:

```
protected function forwardToAsqAuthoring()
{
	$backLink = self::dic()->ui()->factory()->link()->standard(
	    self::dic()->language()->txt('back'), 
	    self::dic()->ctrl()->getLinkTarget($this, self::CURRENT_COMMAND)); 
	
	$authoring_context_container = new AuthoringContextContainer(
	    $backLink,
	    $this->object->getRefId(),
	    $this->object->getId(),
	    $this->object->getType(),
	    self::dic()->user()->getId(),
	    self::dic()->access()->checkAccess('write', '', $this->object->getRefId()));
	    
	$asq = new ilAsqQuestionAuthoringGUI($authoring_context_container);
	
	self::dic()->ctrl()->forwardCommand($asq);
}
```

### Get question creation link
The authoring question service offers a creation form for questions. You can get the link to this form as follows:

```
$link =AuthoringQuestion::getCreationLink([]);
```

It is recommended that you add your current call stack to the link to create:

```
$link = AuthoringQuestion::getCreationLink(array_map(function($item) {
    return $item['class'];
}, self::dic()->ctrl()->getCallHistory()));
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

## AssessmentFactory
This service provides handy singleton instances for the question service and answer service. It may be accessed using DIC.

```
global $DIC;

// Get answer service
$DIC->assessment()->answer();

// Get question service
$DIC->assessment()->question();
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
A container is the object where questions are created and is thus basically the owner of these questions.
This method is a basic way to get questions of an object and is discuraged from actual use, as Question consumers are expected to store the ids of their quesitons themselves.
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
Not yet implemented. Will trigger the delete event, so that loading the question or trying to perform changes on it throw events.
### Other
#### Get question component

Gets the question component from the service, the question component can be used to display questions.

Example:

```
$question = $question_service->getQuestionByQuestionId($id);
$component = $question_service->getQuestionComponent($question);

$component->renderHtml(); //renders the questions html (returns string)
```

The component can be provided with an answer through the method setAnswer().
The entered answer of the user can be extracted from the component through the method readAnswer();

#### Get question edit form

Returns the form used to edit the question. The form is of the type ilPropertyFormGUI and works like one.
It is easyer to use the provided classes that you can get by using AuthoringQuestion.

example:

```
$question = $DIC->assessment()->question()->getQuestionByQuestionId($question_id);
$form = $DIC->assessment()->question()->getQuestionEditForm($question);
$form->setFormAction($DIC->ctrl()->getFormAction($this, self::CMD_SHOW_FORM));
$form->addCommandButton(self::CMD_SAVE_FORM, $DIC->language()->txt('save'));
$DIC->ui()->mainTemplate()->setContent($form->getHTML());
```

to save:

```
$question = $form->getQuestion();
$DIC->assessment()->question()->saveQuestion($question);
```

#### Get question page

Gets the Question surrounded by the stuff from the ilias object editor. To get the question component in it use the getQuestionComponent Method.

## AnswerService
This service offers means to receive answer-specific data from questions.
```
$answer_service = new AnswerService()
```
### Get score

Get the reached score for the provided answer and question.

```
$score = $answer_service->getScore($question, $answer)
```
### Get max score

Get the maximal possible score of a question.

```
$max_score = $answer_service->getMaxScore($question)
```
### Get best answer

Gets the best answer that is possible on that question.
caveat. some question types do not have a best possible answer (FileUploadQuestions ...)

```
$best_answer = $answer_service->getBestAnswer($question)
```


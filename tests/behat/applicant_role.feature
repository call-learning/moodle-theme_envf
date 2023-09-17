@theme @theme_envf @tool_gdpr_plus
Feature: Within courses and depending on the roles, some user can or cannot do some actions.

  Background:
    # Necessary as the post install script needs to be run so user right are defined in full.
    Given the following config values are set as admin:
      | theme             | envf           |
      | sitepolicyhandler | tool_gdpr_plus |
    And I run all adhoc tasks
    And the following "tool_gdpr_plus > gdpr_policies" exist:
      | name                | revision | content    | summary     | status | optional | audience | agreementstyle |
      | This site policy    |          | full text2 | short text2 | active | 0        | all      | 0              |
      | This cookies policy |          | full text3 | short text3 | active | 1        | all      | 1              |
    Given the following "users" exist:
      | username   | firstname  | lastname   | email                  | auth   |
      | 10101010   | applicant  | applicant  | s1@example.com         | manual |
    And the following "role assigns" exist:
      | user     | role        | contextlevel | reference |
      | 10101010 | user   | System       |           |
    And the following "courses" exist:
      | fullname | shortname | format   | idnumber | enablecompletion |
      | Course1  | C1        | envfpsup | qcourse  | 1                |
    And I restore "/local/envf/tests/fixtures/sample-questionnaire-course.mbz" backup into "C1" course
    And the following "course enrolments" exist:
      | user     | course | role     |
      | 10101010 | C1     | student  |

  @javascript
  Scenario:
    Given I log in as "admin"
    Given I log in as "10101010"
    Then I should see "J'évalue mes performance"
    When I click on "Complete" "link"
    Then I should see "Course1"
    And I should see "Description 11"
    And I click on "Next Page >>" "button"
    Then I should see "Description 1"
    And I should see "Choice 1"
    Then I set the following fields to these values:
      | Choice 1 | <8 |
      | Choice 2 | <8 |
      | Choice 3 | <8 |
      | Choice 4 | <8 |
      | Choice 5 | <8 |
    And I click on "Next Page >>" "button"
    Then I set the following fields to these values:
      | Choice 7  | <8 |
      | Choice 8  | <8 |
      | Choice 9  | <8 |
      | Choice 10 | <8 |
      | Choice 11 | <8 |
      | Choice 12 | <8 |
      | Choice 13 | <8 |
      | Choice 14 | <8 |
    And I click on "Next Page >>" "button"
    Then I set the following fields to these values:
      | Choice 23 | d'accord |
      | Choice 24 | d'accord |
      | Choice 25 | d'accord |
      | Choice 26 | d'accord |
      | Choice 27 | d'accord |
      | Choice 28 | d'accord |
    And I press "Submit questionnaire"
    Then I should see "Your response"
    And I follow "Dashboard" in the user menu
    When I click on "Complete" "link"
    And I set the field "City/town" to "Velaux"
    Then I click on "Update my profile" "button"
    Then I click on "Je confirme que les données du formulaire ci-dessus sont identiques à celles fournies sur le site Parcoursup." "checkbox"
    Then I press "Save my choice"
    And I click on "Étape 4 - Je télécharge mon attestation" "link"
    Then I should see "Get Certificate"
    And I follow "Dashboard" in the user menu
    And I should see "Complete"
    When I click on "Complete" "link"
    Then I press "Get Certificate"
    And I follow "Dashboard" in the user menu
    Then I should see "Download"




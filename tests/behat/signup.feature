@theme @theme_envf
Feature: The user should be logged in directly when signing up and be shown the dashboard.

  Background:
    And the following "users" exist:
      | username | firstname | lastname | email               |
      | 101010   | John      | Doe      | s1@example.com      |
      | manager  | manager   | manager  | manager@example.com |
    And the following "role assigns" exist:
      | user    | role    | contextlevel | reference |
      | manager | manager | System       |           |
    And the following config values are set as admin:
      | registerauth | psup |

  @javascript
  Scenario: As a user I want to create a new account
    Given I am on site homepage
    And I follow "Log in"
    When I press "Create new account"
    And I set the following fields to these values:
      | Parcoursup Identifier | 12345678              |
      | Password              | P@ssword#101A         |
      | Email address         | user1@address.invalid |
      | First name            | User1                 |
      | Surname               | L1                    |
    And I press "Create my new account"
    And I should see "An email should have been sent to your address at user1@address.invalid"
    And I should see "Continue"
    And I press "Continue"
    And I should see "User1 L1" in the ".usermenu" "css_element"




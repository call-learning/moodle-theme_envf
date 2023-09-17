@local @theme_envf
Feature: The user should be logged in directly when signing up and be shown the dashboard.

  Background:
    And the following "users" exist:
      | username | firstname | lastname | email               |
      | 101010   | John      | Doe      | s1@example.com      |
      | manager  | manager   | manager  | manager@example.com |
    And the following "role assigns" exist:
      | user    | role    | contextlevel | reference |
      | manager | manager | System       |           |

  @javascript
  Scenario: As an admin I should be able to manage page
    Given I log in as "admin"
    And I navigate to "Mini CMS Page management > Manage pages" in site administration
    And I click on "Add" "button"
    Then I should see "Page title"
    Then I set the following fields to these values:
    | Page title | title page |
    | Page Shortname | page shortname |
    | Page Description | page desc. lorem ispsum |
    Then I press "Save"
    And I click on ".fa-search[label=\"View page\"]" "css_element"
    And I should see "page desc. lorem ispsum"
    And I should see "page shortname"

  @javascript
  Scenario: As an manager I should be able to manage page created by an admin
    Given I log in as "admin"
    And I navigate to "Mini CMS Page management > Manage pages" in site administration
    And I click on "Add" "button"
    Then I should see "Page title"
    Then I set the following fields to these values:
      | Page title | title page |
      | Page Shortname | page shortname |
      | Page Description | page desc. lorem ispsum |
    Then I press "Save"
    And I log out
    And I log in as "manager"
    And I navigate to "Mini CMS Page management > Manage pages" in site administration
    Then I should see "title page"
    And I click on ".fa-cog[label=\"Edit page\"]" "css_element"
    Then I set the following fields to these values:
      | Page title | title page |
      | Page Shortname | page shortname |
      | Page Description | New page description |
    Then I press "Save"
    And I click on ".fa-search[label=\"View page\"]" "css_element"
    And I should not see "page desc. lorem ispsum"
    And I should see "New page description"


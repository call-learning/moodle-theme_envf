@theme @theme_envf
Feature: The user should be presented with a front page and menus

  Background:
    Given the following config values are set as admin:
      | sitepolicyhandler | tool_gdpr_plus |
    # This is required for now to prevent the overflow region affecting the action menus.
    And the following "tool_gdpr_plus > gdpr_policies" exist:
      | name                | revision | content    | summary     | status | optional | audience | agreementstyle |
      | This site policy    |          | full text2 | short text2 | active | 0        | all      | 0              |
      | This cookies policy |          | full text3 | short text3 | active | 1        | all      | 1              |
      | This privacy policy |          | full text4 | short text3 | active | 0        | loggedin | 0              |
    And the following "users" exist:
      | username | firstname | lastname | email           |
      | user1    | User      | One      | one@example.com |

  Scenario: As a user I should see
    Given I am on homepage
    Then I should see "Écoles nationales vétérinaires de France" in the "#page-footer" "css_element"
    And I should see "If you want to continue browsing this website"



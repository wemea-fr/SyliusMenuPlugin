@managing_menu_item
Feature: Having error message
  In order manage item with correct value
  As an administrator
  I have error validation message for invalid field


  Background:
    Given the store is available in en_US
    And the store is also available in fr_FR
    And the store has one menu
    And I am logged in as an administrator


  @ui
  Scenario: The title is required
    Given I want to create a new item for this menu
    And I select "Custom link" as link type
    And I set "http://domain.com/my/link.html" for custom link
    When I add it
    Then I should be notified the title is required in "English (United States)" with message "Please, fill the menu title."

  @ui
  Scenario: the description is no required
    Given I want to create a new item for this menu
    And I select "Custom link" as link type
    And I set "http://domain.com/my/link.html" for custom link
    And I set "My link" for title in "English (United States)"
    And I set "Mon lien" for title in "French (France)"
    When I add it
    Then I should be notified that it has been successfully created

  @ui
  Scenario: the title is required only on default locale
    Given I want to create a new item for this menu
    And I select "Custom link" as link type
    And I set "http://domain.com/my/link.html" for custom link
    And I set "My link" for title in "English (United States)"
    When I add it
    Then I should be notified that it has been successfully created


  @ui
  Scenario: The resource target is required
    Given I want to create a new item for this menu
    And I set "My link" for title in "English (United States)"
    And I select "Link to product" as link type
    When I add it
    Then I should be notified the resource target of the link is required with message "Please, defined the link's target."

  @ui
  Scenario: it validate the selected type
    Given I want to create a new item for this menu
    And I set "My link" for title in "English (United States)"
    And I select "Custom link" as link type
    And I set "http://domain.com/my/link.html" for custom link

    And I select "Link to product" as link type
    When I add it
    Then I should be notified the resource target of the link is required with message "Please, defined the link's target."


  @ui
  Scenario: A regex validate the custom URL
    Given I want to create a new item for this menu
    And I set "My link" for title in "English (United States)"
    And I select "Custom link" as link type
    And I set "bad-url" for custom link
    When I add it
    Then I should be notified the custom URL in "English (United States)" is not valid with the message: "The URL should start with "/" (internal link) or with "http://" or "http://" (external website)"

  @ui
  Scenario: Blank violation error is mapped on custom translation link
    Given I want to create a new item for this menu
    And I set "My link" for title in "English (United States)"
    And I select "Custom link" as link type
    And I set "/my/link" for custom link in "French (France)"
    And I set "  " for custom link in "English (United States)"
    When I add it
    Then I should be notified the custom URL in "English (United States)" can not be blank with the message: "This value can not be null"


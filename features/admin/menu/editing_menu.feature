@editing_menu
Feature: I can edit menu
  In order to manage menu
  As an administrator
  I can edit menu

  Background:
    Given the store operates on a single channel in "United States"
    And the store is available in en_US
    And the store has one menu
    And I am logged in as an administrator

  @ui
  @integration_test
  @integration_test_menus_plugin
  Scenario: I can access to the update page
    Given I try to edit this menu
    Then I should be on edit page of this menu

  @ui
  @integration_test
  @integration_test_menus_plugin
  Scenario: I should see each field
    Given I want to edit this menu
    Then I should see the "code", the "enabled" and the "visibility" fields
    And I should see the "channels" field
    And the code field is disabled
    And I should see "title" field in "English (United States)"

  @ui
  Scenario: I can edit menu
    Given I want to edit this menu
    # Check before the current value
    And the value of visibility field is Public
    And the menu is enabled

    When I select Private as visibility
    And I disabled this menu
    And I set "my title" as "title" in "English (United States)"
    And I save my changes

    Then I should be notified that it has been successfully edited
    And the value of visibility field is Private
    And the menu is disabled
    And the "title" is "my title" in "English (United States)"


  @ui
  Scenario: The title is required
    Given I want to edit this menu
    When I set empty value for "title" in "English (United States)"
    And I save my changes
    Then I should be notified "title" is required on "English (United States)" with message "Please, fill the menu title."


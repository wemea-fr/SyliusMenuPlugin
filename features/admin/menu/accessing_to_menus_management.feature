@accessing_to_menus_page
Feature: I can access at the menu management page
  In order to manage store menus
  As an administrator
  I can access to menu admin page

  Background:
    Given the store operates on a single channel in "United States"
    And the store is available in en_US
    And the store has one menu
    And I am logged in as an administrator

  @ui
  @integration_test
  @integration_test_menus_plugin
  Scenario: I access at admin menu item page
    Given I try to access at admin menu page
    Then I should be on admin menu page

  @ui
  @integration_test
  @integration_test_menus_plugin
  Scenario: I should see expected columns
    Given I am on admin menu page
    Then I should see 1 menu
    And I should see "Code", "Title" and "Enabled" columns
    And I should see "Channels", "Visibility" and "Actions" columns

  @ui
  Scenario: I can list items about menu
    Given this menu has one item
    When I want to manage menus
    Then I should see "Manage items" button with "List items" and "Create item" options
    When I click on "list items" option for this menu
    Then I should be on the items page of this menu

  @ui
  Scenario: I should not see list items option menu has any items
    Given I want to manage menus
    Then I should see "Manage items" button without "List items" option
    And I should see "Manage items" button with "Create item" option

  @ui
  Scenario: I can edit menu
    Given I want to manage menus
    Then I should see "Edit" button
    When I click on "Edit" button of this menu
    Then I should be on edit page of this menu

 @ui
 Scenario: I should see filters
   Given I want to manage menus
   Then I should see search filter
   And I should see enabled filter
   And I should see visibility filter


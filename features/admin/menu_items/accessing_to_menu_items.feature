@accessing_to_menu_item_page
@integration_test
@integration_test_menus_plugin
Feature: I can access at the menu management page
  In order to manage links about menus
  As an administrator
  I can access to menu admin item page

  Background:
    Given the store operates on a single channel in "United States"
    And the store is available in en_US
    And the store has one menu
    And this menu has one item
    And I am logged in as an administrator

  @ui
  Scenario: I access at admin menu page
    Given I try to access at the items page of this menu
    Then I should be on the items page of this menu

  @ui
  Scenario: I should see expected columns
    Given I want to manage items of this menu
    Then I should see 1 item
    And I should see "Link's icon" column on the grid
    And I should see "Link's target", "Priority" and "Link's type" columns on the grid
    And I should see "CSS classes" column too on the grid

  @ui
  Scenario: There is the menu parent on the breadcrumb
    Given I want to manage items of this menu
    Then I should see code of this menu on breadcrumb
    When I click parent menu link on the breadcrumb
    Then I should be on edit page of this menu

  @ui
  Scenario: I can create new item
    Given I want to manage items of this menu
    Then I should see create button
    When I click on create button
    Then I should be on create page

@viewing_menu
Feature: I should see menu
  In order to view each menu
  As a visitor/customer
  I should see menu according to the Back office configuration

  Background:
    Given the store operates on a single channel in "United States"
    And the store has one menu with code homepage_footer_menu and "More links" as title
#    Make it enabled and public by default
    And this menu is enabled on this channel
    And this menu is public

  @ui
  Scenario: I should see menu with title
    Given I go to the homepage
    Then I should see the homepage_footer_menu menu
    And the title of the homepage_footer_menu menu is "More links"

@viewing_menu
Feature: I should see menu according the background configuration
  In order to view each menu
  As a visitor/customer
  I should see menu according to the Back office configuration

  Background:
    Given the store operates on a single channel in "United States"
    And the store has one menu with code homepage_footer_menu
#    Make it enabled and public by default
    And this menu is enabled on this channel
    And this menu is public

  @ui
  Scenario: I viewing public and menu as a guest
    Given I go to the homepage
    Then I should see the homepage_footer_menu menu

  @ui
  Scenario: I viewing public menu as a customer
    Given I am a logged in customer
    And I go to the homepage
    Then I should see the homepage_footer_menu menu

  @ui
  Scenario: I should not see private menu as a guest
    Given this menu is private
    When I go to the homepage
    Then I should not see the homepage_footer_menu menu

  @ui
  Scenario: I should see private menu as a customer
    Given this menu is private
    And I am a logged in customer
    When I go to the homepage
    Then I should see the homepage_footer_menu menu

  @ui
  Scenario: I not see menu if is disabled on this channel
    Given this menu is disabled on this channel
    When I go to the homepage
    Then I should not see the homepage_footer_menu menu

  @ui
  Scenario: I should not see a disabled menu
    Given this menu is disabled
    When I go to the homepage
    Then I should not see the homepage_footer_menu menu

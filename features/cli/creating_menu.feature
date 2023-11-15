@creating_menu
@menu_command
Feature: I can create menu with command
  In order to create a new menu
  As an developer
  I can use symfony command to create this menu

  Background:
    Given the store operates on a channel identified by default_store code
    And the store also operates on another channel named "secondary_store"

  @cli
  Scenario: I create one menu
    Given the menu new_menu should not appear on the database
    When I run command to create the menu new_menu
    Then I should be notified the menu was successfully created
    And the menu new_menu appear on the database

  @cli
  Scenario: By default one menu is enabled on all channels
    Given I run command to create the menu new_menu
    Then I should be notified the menu was successfully created
    And the menu "new_menu" should be enabled on default_store and secondary_store channels

  @cli
  Scenario: I can specified expected channel where menu is enabled
    Given I run command to create the menu new_menu with specified "secondary_store" as channel
    Then I should be notified the menu was successfully created
    And the menu "new_menu" should be enabled on secondary_store channel
    And the menu "new_menu" should not be enabled on default_store channel


 @cli
 Scenario: I can specified one or more channels
  Given I run command to create the menu new_menu with specified "secondary_store, default_store" as channel
  Then I should be notified the menu was successfully created
  And the menu "new_menu" should be enabled on default_store and secondary_store channels

 @cli
 Scenario: I should be notified if one or more channel not found
  Given I run command to create the menu new_menu with specified "undefined_channel" as channel
  Then I should be notified the menu was successfully created
  And I should be notified one channel not found with the message "One ore more channels not found"
  And the menu "new_menu" should not be enabled on default_store and secondary_store channels


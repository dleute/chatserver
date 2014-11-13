Feature: Create chat
  In order to talk to others
  As a logged in user
  I need to be able to create a chat

  @javascript
  Scenario: Creating chat named friends
    Given I am logged in as "testuser1"
    And I press "New Chat"
    And I wait "1000" for AJAX to finish
    When I enter "friends" in "name" field
    And I press "Create Chat"
    And I wait "1000" for AJAX to finish
    Then I should see "friends" in the "#chatTab" element

  @javascript
  Scenario: Don't allow duplicate chat names
    Given I am logged in as "testuser1"
    And I press "New Chat"
    And I wait "2000" for AJAX to finish
    When I enter "duplicate" in "name" field
    And I press "Create Chat"
    And I wait "2000" for AJAX to finish
    And I press "New Chat"
    And I wait "2000" for AJAX to finish
    When I enter "duplicate" in "name" field
    And I wait "2000" for AJAX to finish
    And I press "Create Chat"
    And I wait "2000" for AJAX to finish
    Then I should see "This value is already used" in the "#chatModal" element
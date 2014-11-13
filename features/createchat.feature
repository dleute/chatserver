Feature: Create chat
  In order to talk to others
  As a logged in user
  I need to be able to create a chat

  @javascript
  Scenario: Creating a chat named friends
    Given I am logged in as "testuser1"
    And I press "New Chat"
    And I wait "1000" for AJAX to finish
    When I fill in "name" with "fuzzy"
    And I press "Create Chat"
    And I wait "1000" for AJAX to finish
    Then I should see "fuzzy" in the "#chatTab" element
Feature: Send a message in chat
  In order to talk to others
  As a logged in user
  I need to be able to send a message in a chat

  @javascript
  Scenario: Send message
    Given I am logged in as "testuser1"
    And I press "New Chat"
    And I wait "2000" for AJAX to finish
    When I enter "message" in "name" field
    And I press "Create Chat"
    And I wait "2000" for AJAX to finish
    And I follow "message"
    And I wait "2000" for AJAX to finish
    And I enter "chatting like a pro" in "content" field
    When I press "Send Message"
    And I wait "2000" for AJAX to finish
    Then I should see "chatting like a pro" in the "#chatTabContent" element
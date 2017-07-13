Feature: login

  Scenario: Logging in an invalid user without cookies
    Given A login request with "user0" "password" is sent without cookies
    Then The URL should redirect to "http://localhost:8080/index.php/login?user=user0&redirect_url="

  Scenario: Logging in an invalid user with cookies
    Given A login request with "user0" "password" is sent with cookies
    Then The URL should redirect to "http://localhost:8080/index.php/login?user=user0&redirect_url="

  Scenario: Logging in a valid user without cookies
    Given User "user0" exists
    And A login request with "user0" "password" is sent without cookies
    Then The URL should redirect to "http://localhost:8080/index.php/apps/files/"

  Scenario: Logging in a valid user with cookies
    Given User "user0" exists
    And A login request with "user0" "password" is sent with cookies
    Then The URL should redirect to "http://localhost:8080/index.php/apps/files/"

Feature: login

  Scenario: Logging in an invalid user without cookies
    Given A login request with "user0" "password0" is sent without cookies
    Then The URL should redirect to "/index.php/login"

  Scenario: Logging in an invalid user with cookies
    Given A login request with "user0" "password0" is sent with cookies
    Then The URL should redirect to "/index.php/login"

  Scenario: Logging in a valid user without cookies
    Given User "user0" exists
    And A login request with "user0" "password0" is sent without cookies
    Then The URL should redirect to "/index.php/apps/files"

  Scenario: Logging in a valid user with cookies
    Given User "user0" exists
    And A login request with "user0" "password0" is sent with cookies
    Then The URL should redirect to "/index.php/apps/files"

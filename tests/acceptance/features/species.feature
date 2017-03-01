Feature: Co-Chair Can manage Species

  I can manage Species including:

  - Species name
  - Basic Description Field
  So that Species can be referenced in PICO questions and research paper nodes

  @api
  Scenario:
    Given I am logged in as a user with the "co_chair" role
    Then I am on "/admin/structure/species/add"
    Then I fill in "edit-name-0-value" with "Canis lupus familiaris"
    Then I fill in "edit-description-0-value" with "Donec id elit non mi porta gravida at eget metus."
    Then I submit the form with id "#edit-submit"
    Then I should see the text "Created the Canis lupus familiaris species."

  @api
  Scenario:
    Given I am an anonymous user
    Then I am on "/admin/structure/species/add"
    Then I should see "Access Denied"

# TheyWorkForYou

They Work For You API

## Requirements

This plugin requires Craft CMS 4.7.0 or later, and PHP 8.0.2 or later.

## Endpoints

- `they-work-for-you/api/get-contact-details`
- `they-work-for-you/api/get-quota`
- `they-work-for-you/api/convert-url`
- `they-work-for-you/api/get-constituency`
- `they-work-for-you/api/get-constituencies`
- `they-work-for-you/api/get-person`
- `they-work-for-you/api/get-mp`
- `they-work-for-you/api/get-mp`
- `they-work-for-you/api/get-mps`
- `they-work-for-you/api/get-mps`
- `they-work-for-you/api/get-lord`
- `they-work-for-you/api/get-lords`
- `they-work-for-you/api/get-mla`
- `they-work-for-you/api/get-mlas`
- `they-work-for-you/api/get-msp`
- `they-work-for-you/api/get-msps`
- `they-work-for-you/api/get-geometry`
- `they-work-for-you/api/get-boundary`
- `they-work-for-you/api/get-committee`
- `they-work-for-you/api/get-debates`
- `they-work-for-you/api/get-wrans`
- `they-work-for-you/api/get-wms`
- `they-work-for-you/api/get-hansard`
- `they-work-for-you/api/get-comments`

All endpoints take the same parameters and provide the same responses as the official TheyWorkForYou API,, the only exception is `they-work-for-you/api/get-contact-details`

### `they-work-for-you/api/get-contact-details`

This end point accepts the following parameters:

- forename: string
- surname: string
- party: string
- constituency: string

If no parameters are passed, all results will be returned.

Example Response:
```JSON
[
  {
    "id": 2,
    "dateCreated": "2024-02-21 11:19:03",
    "dateUpdated": "2024-02-21 11:19:03",
    "forename": "Debbie",
    "surname": "Abrahams",
    "display_as": "Debbie Abrahams",
    "list_as": "Abrahams, Debbie",
    "party": "Labour",
    "constituency": "Oldham East and Saddleworth",
    "email": "abrahamsd@parliament.uk",
    "address_1": "House of Commons",
    "address_2": "London",
    "postcode": "SW1A 0AA"
  },
]
```
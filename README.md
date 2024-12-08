# AWS Laravel Secrets

This is just a tiny and basic helper package to work with Secrets in AWS.

## Usage
To use this you will need to have AWS credentials. Typically a secret would be created manually and then rotated via a Laravel schedule.


### Create a secret
Secrets can created from the AwsSecrets class programmatically, or through the console command:
`aws-secret:create {your-secret-name} {optional-secret-value}` 

### Get a secret
Secrets can retrieved from the AwsSecrets class programmatically, or through the console command:
`aws-secret:get {your-secret-name}` 

### Rotate a secret
Secrets can be rotated via a console command:
`aws-secret:rotate {your-secret-name} {optional-secret-value}`

In all cases if you do not choose a secret value, one will be generated automatically.

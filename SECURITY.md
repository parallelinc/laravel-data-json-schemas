# Security Policy

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |

## Reporting a Vulnerability

We take the security of Laravel Data JSON Schemas seriously. If you discover a security vulnerability within the package, please follow these steps:

1. **DO NOT** open a public GitHub issue
2. Send a direct message on X/Twitter to [@BasilLangevin](https://twitter.com/BasilLangevin) with:
   - A clear description of the vulnerability
   - Steps to reproduce the vulnerability
   - Possible impacts of the vulnerability
   - Any potential fixes if you have them

## What to Expect

When you report a vulnerability:

1. You'll receive an acknowledgment of your report within 48 hours
2. We'll investigate the issue and keep you informed of my progress
3. Once we've determined the impact and scope:
   - we'll develop and test a fix
   - we'll create a new security release if necessary
   - we'll disclose the vulnerability after a fix is available

## Security Best Practices When Using This Package

1. **Schema Validation**: Always validate your JSON Schemas on a trusted server before using them in production.

2. **Access Control**: Ensure that your JSON Schema endpoints are properly protected with appropriate authentication and authorization.

3. **Input Validation**: Ensure any user input is validated using a JSON Schema validator AND the `spatie/laravel-data` built-in validation/Laravel validation.

4. **API Keys**: If your JSON Schemas contain sensitive information or are used with external APIs, ensure proper key management and never expose API keys in the schemas.

## Responsible Disclosure

We kindly ask you to:

- Give us reasonable time to investigate and fix the vulnerability before disclosing it to others
- Make a good faith effort to avoid privacy violations, data destruction, and service interruption
- Not exploit the vulnerability beyond what is necessary to demonstrate the security issue

## Hall of Fame

We're grateful to the following security researchers who have responsibly disclosed vulnerabilities:

_(This section will be updated as contributions are made)_

## Contact

For any security-related questions, please send a direct message on X/Twitter to [@BasilLangevin](https://twitter.com/BasilLangevin).


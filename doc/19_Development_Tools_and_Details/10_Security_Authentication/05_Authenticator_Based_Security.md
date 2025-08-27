# Authenticator Based Security

As OpenDXP uses the Symfony Security Component for authentication/authorization of Admin interface and also 
provides the capabilities to use the same security component on frontend websites. It is important to adapt the ongoing 
changes in Symfony security component. 

## Points to consider when moving to new Authenticator:

- New authentication system works with `Password Hasher Factory` instead of `Encoder Factory`.
- `BruteforceProtectionHandler` will be replaced with `Login Throttling`.
- `Custom Guard Authenticator` will be replaced with `Http\Authenticator`.
- Anonymous user no longer exist.

For more information on new Authenticator Based Security, please read the
[Symfony Security Component documentation](https://symfony.com/doc/current/security.html).

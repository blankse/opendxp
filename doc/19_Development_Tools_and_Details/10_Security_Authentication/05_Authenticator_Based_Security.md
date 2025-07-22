# Authenticator Based Security

> Note: This feature is available since v10.5

As OpenDXP uses the Symfony Security Component for authentication/authorization of Admin interface and also 
provides the capabilities to use the same security component on frontend websites. It is important to adapt the ongoing 
changes in Symfony security component. 

As starting with Symfony 5.3, a new Authenticator based security is introduced and old authentication system is 
deprecated. It is highly recommended to migrate to new Authentication system.

By default, OpenDXP uses old authentication system for backward compatibility reasons. 
To use new authenticator, add symfony config:

```yaml
security:
    enable_authenticator_manager: true
```

and refactor `security.yaml` to adapt new changes.  

## Points to consider when moving to new Authenticator:

- New authentication system works with `Password Hasher Factory` instead of `Encoder Factory`.
- `BruteforceProtectionHandler` will be replaced with `Login Throttling`.
- `Custom Guard Authenticator` will be replaced with `Http\Authenticator`.
- Anonymous user no longer exist.

For more information on new Authenticator Based Security, please read the
[Symfony Security Component documentation](https://symfony.com/doc/current/security.html).

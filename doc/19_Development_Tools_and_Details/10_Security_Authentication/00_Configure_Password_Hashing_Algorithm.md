# Configure Password Hashing Algorithm

OpenDXP uses PHP's default password hashing algorithm by default, which currently equals to `BCrypt` with a cost of `10`
(see [`PASSWORD_DEFAULT`](https://www.php.net/manual/en/password.constants.php#constant.password-default)), but the algorithm 
can also be configured (see here for [possible algorithms and their options](https://www.php.net/manual/en/password.constants.php)),
for example:

 ```yaml
opendxp:
    security:
        password:
            algorithm: !php/const PASSWORD_BCRYPT
            options:
                cost: 13
  ```

This config will be used for OpenDXP's backend users and [fields of type `Password` in custom OpenDXP Objects](./01_Authenticate_OpenDxp_Objects.md).
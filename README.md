<p align="center">
  <img src="jwt.png" style="border:1px solid #ddd;width:auto;">
</p>

<p align="center">
  <a href="https://travis-ci.org/rluders/oc-jwtauth-plugin">
    <img src="https://travis-ci.org/rluders/oc-jwtauth-plugin.svg?branch=main">
  </a>
  <a href="https://wintercms.com/plugin/rluders-jwtauth">
    <img src="https://img.shields.io/badge/Winter%20CMS-Plugin-%23EE7203.svg">
  </a>
  <a href="https://www.patreon.com/rluders">
    <img src="https://img.shields.io/badge/Support_on-Patreon-green.svg">
  </a>
  <a href="https://www.gnu.org/licenses/gpl-3.0.en.html">
    <img src="https://img.shields.io/github/license/rluders/oc-jwtauth-plugin.svg">
  </a>
</p>

# Introduction

This plugin provides a JSON Web Tokens authentication mechanism for [Winter CMS](http://www.wintercms.com) integrated with `Winter.User`. It's essential for your web application built with Angular, Vue.js, React or other modern Javascript frameworks.

## Requirements

- [Winter.User](https://github.com/wintercms/wn-user-plugin) plugin
- [RLuders.CORS](https://wintercms.com/plugin/rluders-cors) plugin (optional, but recommended)

## Theme

- [Angular Skeleton](https://wintercms.com/theme/rluders-angular2)

## Tutorials

- [Creating Vue, Vuex and Winter CMS APP - Login and Register](https://www.youtube.com/watch?v=8S3DySmMOuk) by [Watch and Learn](https://www.youtube.com/channel/UCXQC_GB5hG6PkzIhSMZ-hWA)

# Installation

```sh
$ composer require rluders/jwtauth
```

# Configuration

You must set a secret token for your application. Do do it, on Winter's Backend access: _Settings > Users > JWTAuth_

# Usage

Here's the list of available endpoints for this plugin.

> If you are using [**Postman**](https://www.getpostman.com/), you can [click here to import the collection](https://www.getpostman.com/collections/5667c055f6f81ff3f821) with all the calls that you need to test it.

## Login

`POST /api/auth/login`

**Route name**

`api.auth.login`

### Parameters

| Name     | Type   | Required | Description             |
| -------- | ------ | -------- | ----------------------- |
| login    | string | Yes      | Account login attribute |
| password | string | Yes      | Account password        |

> The field `login` value can be the account `email` or `username`. You can select it on `Winter.User` configuration what field should be used for login.

### Responses

**SUCCESS**

> Code: 200

```json
{
  "token": string,
  "user": object
}
```

**ERROR**

> Code: 401

```json
{
  "error":
    invalid_credentials |
    could_not_create_token |
    user_inactive |
    user_is_banned
}
```

## Register

`POST /api/auth/register`

**Route name**

`api.auth.register`

### Parameters

| Name                  | Type   | Required | Description              |
| --------------------- | ------ | -------- | ------------------------ |
| username              | string | No       | Account username         |
| email                 | string | Yes      | Account email            |
| password              | string | Yes      | Account password         |
| password_confirmation | string | No       | Confirm the new password |

> The field `username` can be **required**. It depends of your `Winter.User` configuration.

### Responses

**SUCCESS**

> Code: 201

```json
[]
```

**ERROR**

> Code: 401

```json
{
  "error": object | registration_disabled
}
```

### Supported events

- `Winter.User.beforeRegister`
- `Winter.User.register`

## Account Activation

`POST /api/auth/account-activation`

**Route name**

`api.auth.account-activation`

### Parameters

| Name            | Type   | Required | Description             |
| --------------- | ------ | -------- | ----------------------- |
| activation_code | string | Yes      | Account activation code |

### Responses

**SUCCESS**

> Code: 200

```json
[]
```

**ERROR**

> Code: 422

```json
{
  "error": invalid_activation_code | invalid_user | user_not_found
}
```

## Forgot Password

`POST /api/auth/forgot-password`

**Route name**

`api.auth.forgot-password`

### Parameters

| Name  | Type   | Required | Description   |
| ----- | ------ | -------- | ------------- |
| email | string | Yes      | Account email |

### Responses

**SUCCESS**

> Code: 200

```json
[]
```

**ERROR**

> Code: 404

```json
{
  "error": user_not_found
}
```

## Reset Password

`POST /api/auth/reset-password`

**Route name**

`api.auth.reset-password`

### Parameters

| Name                  | Type   | Required | Description              |
| --------------------- | ------ | -------- | ------------------------ |
| reset_password_code   | string | Yes      | Reset password code      |
| password              | string | Yes      | Account new password     |
| password_confirmation | string | No       | Confirm the new password |

### Responses

**SUCCESS**

> Code: 200

```json
[]
```

**ERROR**

> Code: 422

```json
{
  "error":
    invalid_reset_password_code | invalid_user | invalid_reset_password_code
}
```

## Refresh Token

`POST /api/auth/refresh-token`

**Route name**

`auth.api.refresh-token`

### Parameters

| Name  | Type   | Required | Description        |
| ----- | ------ | -------- | ------------------ |
| token | string | Yes      | Valid user JWToken |

### Responses

**SUCCESS**

> Code: 200

```json
{
  "token": string
}
```

**ERROR**

> Code: 403

```json
{
  "error": could_not_refresh_token | given_token_was_blacklisted
}
```

## Get User

`GET /api/auth/me`

**Middleware**

`jwt.auth`

**Route name**

`api.auth.me`

### Parameters

| Name  | Type   | Required | Description |
| ----- | ------ | -------- | ----------- |
| token | string | Yes      | Valid token |

### Responses

**SUCCESS**

> Code: 200

```json
{
  "user": object
}
```

**ERROR**

> Code: 404

```json
{
  "error": user_not_found
}
```

# Known issues

Beside the fact that I'm always trying to solve the possible issues, bad things could happen. Here, an list of possible issues and how to fix it.

## Note to Apache users

In order to use the authorization Bearer Token you must add the following code to your `.httaccess`

```
RewriteEngine On
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
```

# License

GPLv3

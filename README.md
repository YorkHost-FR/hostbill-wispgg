# Introduction

> Don't have the module yet? [Get it!](https://github.com/xephia-eu/hostbill-wispgg)

This is an un-official [Wisp.gg](https://wisp.gg) Hosting Module for [Hostbill](hostbillapp.com/) created and maintained by Xephia.

# Features

- [Charge for *CPU, Disk Space, Memory, Swap, Dedicated IP, Location*](#)
- [Custom Variables *Use custom variables, like `$slots` in egg variable*](#)
- [Multiple ports *Set how many additional ports you want, you can also use these in egg variables*](#)

# Installation

> When upgrading, you only need to follow steps **1.** and **2.**

1. [Download the module's source code](https://github.com/xephia-eu/hostbill-wispgg) and put it into `wisp` folder.
2. Upload the `wisp` directory to your `/includes/modules/Hosting` folder.
3. Log in to your Hostbill instance and go to **Setings** > **Modules**.
4. Search for **Wisp.gg** and activate the module.
5. Go to **Settings** > **Apps Connections**, click on the **Add new Connection** button.
6. Select `Wisp.gg` as Application and enter the hostname of your panel (eg. `game.xephia.eu`) and your Api Application Key.
7. Check the `Secure` box if your panel is on SSL.
8. Test the connection and press **Add new Connection**.

# Setting up a product with Wisp.gg

1. After creating a product go to the **Connect with App** page on the product details page.
2. Select `Wisp.gg` as **App** and your app connection.
3. You can either press **Get values from server** or fill the fields in **Resource Limits** manually.
4. After filling the values as you desire, click save and you're done.

# Custom egg variables

## Hostbill Components

You can either use Hostbill components as values:

```
SERVER_JARFILE:server.jar;
VANILLA_VERSION:$version;
```

Where `$version` is a dropdown component containing values like '1.19.2', '1.18.2', etc..

## Ports as variables

Or you can use your main port, or even your allocations as variables.

```
SERVER_JARFILE:server.jar;
SERVER_PORT:${port};
PLUGIN1_PORT:${allocation};
PLUGIN2_PORT:${allocation};
```

See how the format is different from the first Hostbill Component example, this is intentional.
Also see how `PLUGIN1_PORT` and `PLUGIN2_PORT` both use the same `${allocation}`, this is because the variable automatically updates on use, so both "plugins" will have different ports.

`${port}` is the main port of the server.
`${allocation}` has maximum usage; this is the number of allocations you have set in **Allocations** field.
For example, if your **Allocations** field is **5**, you can only use `${allocation}` **5** times.
# Releases
```json
{
    "27-04-2019": "Add auto generate readme.md on dist version",
    "13-04-2019": "Add autoprefixer to CSS compiled",
    "06-04-2019": "Add structure recommended in src/assets/scss/style.scss",
    "02-04-2019": [
        "Compile on development phase JS not minified",
        "Listen and compile PHP Files"
    ],
}
```

# Structure
```html
dist
    assets
src
    assets
        css
        fonts
        images
        scripts
            js
               includes
               main.js
        scss
           base
           components
           helpers
           vendors
           style.scss
    *.html
    *.php
    project-info.json
```
# GULP Commands
**default with live reload**
> gulp

**compile before generate dist**
> gulp init-compile

**generate dist files**
> gulp dist
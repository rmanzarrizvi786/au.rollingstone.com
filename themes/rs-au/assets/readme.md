# Asset pipeline based on Webpack.

### Notable features
- **CSS:**
  - [Sass](http://sass-lang.com/)
  - [Autoprefixer](https://github.com/postcss/autoprefixer) - the most popular [PostCSS](https://github.com/postcss/postcss) plugin
  - [CSSNext](http://cssnext.io/) - Use tomorrow’s CSS syntax, today.
- **JS:** 
  - Modular ES6 with [Babel](http://babeljs.io/)
  - [Stage 1](https://github.com/tc39/ecma262#current-proposals) features
  - JS linting with [eslint](http://eslint.org/)
- **Production Builds:**
  - JS and CSS minification
  - Image Optimization

### Basic Usage
Make sure at least Node ^5.4.2 is installed.

#### Install Dependencies
```
npm i
```

#### Start compiling, and watching files
```
npm run dev
```

#### A lighter dev task

This task does not hot module reload, good for CSS changes that do not benefit from it.

```
npm run dev-lite
```

### Build production-ready files
```
npm run prod
```

### Start PHP server
```
npm run server
```

**Note :** _Before building assest for first time, you might need to remove `node_modules/` from assets directory_npm
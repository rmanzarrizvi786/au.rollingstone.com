# _PMC_CORE_ Styles Authoring Guidelines

## Component stylesheets

CSS in this project is written into stylesheets at the component level; there is no global.css. Components should be organized as sub-directories in `client/scss`. Each sub-directory should contain a loader, which should write to a new stylesheet.

Each loader should load a set of selectors pertaining to the component. For example:

```css
/* In component-1.scss */
@import '../core/loader'; /* Loads core vars and mixins */
@import './component-1';
@import './component-1__element';
@import './component-2';
```
```css
/* In component-1.scss */
.component-1 {
	/* Styles */
}
```
```css
/* In component-1__element.css */
.component-1__element {
	/* Styles */
}

.component-1__element--modifier {
	/* Styles */
}
```

## Selector naming

This project uses the **BEM** selector naming convention. [This](https://css-tricks.com/bem-101/) is a good introduction. Among other things, BEM is designed to help the styles author avoid deep nesting. Try not to nest! The linter will yell at you.

Here's a simple example of a set of BEM classes:
```css
.person {}
.person__hand {}
.person--female {}
.person--female__hand {}
.person__hand--left {}
/* src: http://csswizardry.com/2013/01/mindbemding-getting-your-head-round-bem-syntax/ */
```

## PostCSS

SCSS in this project is parsed and post-processed using PostCSS. SCSS syntax and filetypes are perfectly valid, but you may also make use of PostCSS plugins.

### Grid

This project uses the [`lost grid`](https://github.com/peterramsing/lost). This particular plugin is complex enough that it's probably worth looking through the docs.

## Style linting

There is a linter! Listen to it! Docs are [here](https://github.com/stylelint/stylelint).

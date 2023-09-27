# RollingStone SCSS Helpers

This directory contains core SCSS variables that can be loaded into section CSS files. It contains no outputting SCSS.

## Mixins

##### 1. `if-no-side-skins`

Use this mixin to contain styles that should be applied if there ad skins are not present, i.e. html does not have the class `.has-side-skins`.

Example:

```
.c-card {
	@include if-no-side-skins {
		flex-shrink: 0;
	}
}
```

Output:

```
html:not(.has-side-skins) .c-card {
	flex-shrink: 0;
}
```

##### 2. `if-no-side-skins`

Use this mixin to contain styles that should be applied if ad skins are present, i.e. html have the class `.has-side-skins`.

Example:

```
.c-card {
	@include if-side-skins {
		flex-shrink: 0;
	}
}
```

Output:

```
html.has-side-skins .c-card {
	flex-shrink: 0;
}
```
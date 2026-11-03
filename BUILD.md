# Build notes

**There is no Sass build.** `css/main.css` is committed and is the stylesheet the theme ships.
`release.yml` packs the theme with `git archive` and compiles nothing. `sass/` is `export-ignore`d,
so the zip holds no sources. Editing `sass/` changes nothing on a site.

## What the build was

Grunt compiled `sass/main.scss` to `css/main.css` through the Ruby `sass` and `compass` binaries.
Ruby Sass reached end of life in 2019 and Compass in 2016, so the Grunt build was removed.

The sources are Compass-coupled:

- `@import "compass/reset"` (`sass/screen.scss`)
- `@import "compass/utilities/color/contrast"` (`sass/main.scss`)
- Compass mixins: `linear-gradient()` ×25, `border-radius()` ×15, `background-image()` ×6,
  `box-shadow()` ×1

`themes.json` lists four colour variants (blue, red, black, purple). Only blue is published.

## Reviving it

Port the Sass step to Dart Sass (`sass-embedded`):

1. Replace the Compass mixins with native CSS.
2. Replace `compass/reset` with a small reset partial and the contrast helper with a short function.
3. Compile each variant's `colors.scss` before packaging it.
4. Commit the regenerated `css/main.css`, since that file is what ships.

## Old templates

`index.php.tpl` and `sass/colors.scss.tpl` were templates for `index.php` and `sass/colors.scss`.
Nothing generates those files now, so edit `index.php` directly.

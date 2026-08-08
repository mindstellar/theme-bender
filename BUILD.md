# Build notes

**The Sass build no longer runs.** `css/main.css` is committed and is the stylesheet the theme
actually ships — `release.yml` packages the theme with `git archive` and never compiles anything,
and `sass/` is `export-ignore`d so the released zip contains no sources. Editing `sass/` without
regenerating `css/main.css` silently ships stale styles while the repository looks correct.

## What the build was

`Gruntfile.js` compiles `sass/main.scss` to `css/main.css` through `grunt-contrib-sass` with
`compass: true`; `config.rb` sets `output_style = :compressed`, which is why `main.css` is a single
minified line.

Those Grunt plugins do not compile anything themselves — they shell out to the Ruby `sass` and
`compass` binaries. Ruby Sass reached end of life in 2019 and Compass has been unmaintained since
2016, so reviving the build as written means reviving two dead toolchains.

The stylesheet is genuinely Compass-coupled, not incidentally:

- `@import "compass/reset"` (`sass/screen.scss`)
- `@import "compass/utilities/color/contrast"` (`sass/main.scss`)
- Compass mixins throughout: `linear-gradient()` ×25, `border-radius()` ×15,
  `background-image()` ×6, `box-shadow()` ×1

## The colour variants

`themes.json` still defines four: `blue` (`#35C3D9`, slug `bender`), `red` (`#EB6056`), `black`,
and `purple`. Per-variant screenshots exist under `screenshot/<variant>/`. Only blue is published;
the other three have not been built since the toolchain retired.

**A second, independent problem blocks rebuilding them.** The `dist` chain omits the Sass step:

```js
grunt.registerTask('dist:' + key,
  ['template:' + key, 'copy:' + key, 'copy:screenshoot_' + key, 'replace:' + key, 'shell:compress_' + key]);
```

`template:<key>` regenerates `sass/colors.scss` with that variant's colours, but nothing compiles
it afterwards, and `copy:<key>` takes `css/**` verbatim. So `grunt dist` would emit four packages
that differ only in name and screenshot, all carrying whatever `css/main.css` happened to be on
disk. Only the `watch` task pairs `template` with `sass`. Any revival needs
`['template:' + key, 'sass', 'copy:' + key, …]`, whichever Sass implementation is used.

## Reviving it

The tractable route is porting the Sass step to Dart Sass (`sass-embedded`) rather than restoring
Ruby gems:

1. Replace the Compass mixins with native CSS — `border-radius`, `box-shadow` and `linear-gradient`
   need no mixin today.
2. Replace `compass/reset` with a small reset partial and `compass/utilities/color/contrast` with a
   short custom function.
3. Add `sass` to the `dist:<key>` chain so each variant compiles its own `colors.scss`.
4. Commit the regenerated `css/main.css`, since that file is what ships.

## Generated files — do not edit directly

`index.php` and `sass/colors.scss` are generated from `index.php.tpl` and `sass/colors.scss.tpl`
by `grunt-template`, using values from `package.json` and `themes.json`. Editing the generated file
works until someone runs the build, which then reverts it. Header changes belong in the `.tpl`.

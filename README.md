# TYPO3 Extension content_defender

[![Latest Stable Version](https://img.shields.io/packagist/v/ichhabrecht/content-defender.svg)](https://packagist.org/packages/ichhabrecht/content-defender)
[![Build Status](https://img.shields.io/travis/IchHabRecht/content_defender/master.svg)](https://travis-ci.org/IchHabRecht/content_defender)
[![StyleCI](https://styleci.io/repos/90545143/shield?branch=master)](https://styleci.io/repos/90545143)

Define allowed or denied content element types in your backend layouts

## Installation

Simply install the extension with Composer or the Extension Manager.

## Usage

1. You only need to adjust the column configuration of your backend_layout

**Allow certain content element types**

- To restrict content element CTypes to a limited set use `allowed = [list of tt_content.CTypes]`

*Example:*
```
columns {
    1 {
        name = Column with header and textmedia elements
        colPos = 3
        colspan = 6
        allowed = header, textmedia
    }
}
```

**Deny certain content element types**

- To remove content element CTypes use `disallowed = [list of tt_content.CTypes]`

*Example:*
```
columns {
    1 {
        name = Column without divider, plain html and table elements
        colPos = 3
        colspan = 6
        disallowed = div, html, table
    }
}
```

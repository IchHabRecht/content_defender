# TYPO3 Extension content_defender

[![Latest Stable Version](https://img.shields.io/packagist/v/ichhabrecht/content-defender.svg)](https://packagist.org/packages/ichhabrecht/content-defender)
[![Build Status](https://img.shields.io/travis/IchHabRecht/content_defender/master.svg)](https://travis-ci.org/IchHabRecht/content_defender)
[![StyleCI](https://styleci.io/repos/90545143/shield?branch=master)](https://styleci.io/repos/90545143)

Define allowed or denied content element types in your backend layouts

## Installation

Simply install the extension with Composer or the Extension Manager.

## Usage

1. You only need to adjust the column configuration of your backend_layout

**Restrict certain content element fields**

- To allow a limited set of values for content element fields use `allowed.field = [list of comma separated values]`

*Examples:*
```
columns {
    1 {
        name = Column with header and textmedia elements
        colPos = 3
        colspan = 6
        allowed {
            CType = header, textmedia
        }
    }
}
```

```
columns {
    1 {
        name = Column with News plugin only
        colPos = 3
        colspan = 6
        allowed {
            CType = list
            list_type = news_pi1
        }
    }
}
```

**Deny certain content element types**

- To remove a set of values from content element fields use `disallowed.field = [list of comma separated values]`

*Examples:*
```
columns {
    1 {
        name = Column without divider, plain html and table elements
        colPos = 3
        colspan = 6
        disallowed {
            CType = div, html, table
        }
    }
}
```

```
columns {
    1 {
        name = Column with header and list, without News plugin
        colPos = 3
        colspan = 6
        allowed {
            CType = header, list
        }
        disallowed {
            list_type = news_pi1
        }
    }
}
```

**Limit the number of content elements**

- To restrict the number of content elements use `maxitems = [number of elements]`

*Example:*
```
columns {
    1 {
        name = Column with one textmedia 
        colPos = 3
        colspan = 6
        allowed {
            CType = textmedia
        }
        maxitems = 1
    }
}
```

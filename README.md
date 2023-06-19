# TYPO3 Extension content_defender

[![Latest Stable Version](https://img.shields.io/packagist/v/ichhabrecht/content-defender.svg)](https://packagist.org/packages/ichhabrecht/content-defender)
[![Build Status](https://img.shields.io/travis/IchHabRecht/content_defender/main.svg)](https://travis-ci.org/IchHabRecht/content_defender)
[![StyleCI](https://styleci.io/repos/90545143/shield?branch=main)](https://styleci.io/repos/90545143)

Content Defender adds more options to define allowed or denied content element types in your backend layouts

## Installation

This extension can be installed using Composer or the Extension Manager.

### Composer

```
composer require ichhabrecht/content-defender
```

### Extension Manager

See [TYPO3 Documentation - Managing Extensions - Legacy Guide](https://docs.typo3.org/m/typo3/tutorial-getting-started/main/en-us/Extensions/LegacyManagement.html)

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

**Combine multiple content element fields**

- The example allows multiple content element types (text and list) while restricting plugin types to `news` only.

*Example:*
```
columns {
    1 {
        name = A column with restricted list_type and "normal" CType
        colPos = 3
        colspan = 6
        allowed {
            CType = textmedia, list
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

## Known issues

### TypeError
*count(): Argument #1 ($value) must be of type Countable|array, null given*

Content Defender needs the runtime cache to be available. Please ensure that this cache **doesn't use** a
\TYPO3\CMS\Core\Cache\Backend\NullBackend backend.

## Community

- Thanks to [b13](https://b13.com) that sponsored the maintenance of this extension with a sponsorship
- Thanks to [Wolfgang Wagner](https://wwagner.net) who sponsored the maintenance of this extension with a sponsorship
- Thanks to [WACON Internet GmbH](https://www.wacon.de) that sponsored the maintenance of this extension with a [GitHub sponsorship](https://github.com/sponsors/IchHabRecht)
- Thanks to [Daniel Goerz](https://twitter.com/ervaude) who wrote about content_defender in his blog [useTYPO3](https://usetypo3.com/backend-layouts.html)
- Thanks to [Marcus Schwemer](https://twitter.com/MarcusSchwemer) who wrote about content_defender in his blog [TYPO3worx](https://typo3worx.eu/2017/07/typo3-extension-roundup-q1-q2-2017/)

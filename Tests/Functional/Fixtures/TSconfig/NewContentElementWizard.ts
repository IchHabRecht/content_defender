mod.wizards.newContentElement.wizardItems >
mod.wizards.newContentElement.wizardItems {
    common {
        header = Typical page content
        show = header, textmedia, bullets, table, uploads
        elements {
            header {
                iconIdentifier = content-header
                title = Header
                tt_content_defValues {
                    CType = header
                }
            }

            textmedia {
                iconIdentifier = content-textpic
                title = Text & Media
                tt_content_defValues {
                    CType = textmedia
                    imageorient = 17
                }
            }

            bullets {
                iconIdentifier = content-bullets
                title = Bullet List
                tt_content_defValues {
                    CType = bullets
                }
            }

            table {
                iconIdentifier = content-table
                title = Table
                tt_content_defValues {
                    CType = table
                }
            }

            uploads {
                iconIdentifier = content-special-uploads
                title = File Links
                tt_content_defValues {
                    CType = uploads
                }
            }
        }
    }

    special {
        header = Special elements
        show = menu, html, div, shortcut
        elements {
            menu {
                iconIdentifier = content-special-menu
                title = Special Menus
                tt_content_defValues {
                    CType = menu
                    menu_type = 0
                }
            }

            html {
                iconIdentifier = content-special-html
                title = Plain HTML
                tt_content_defValues {
                    CType = html
                }
            }

            div {
                iconIdentifier = content-special-div
                title = Divider
                tt_content_defValues {
                    CType = div
                }
            }

            shortcut {
                iconIdentifier = content-special-shortcut
                title = Insert records
                tt_content_defValues {
                    CType = shortcut
                }
            }
        }
    }

    forms.header = Form elements
    plugins {
        header = Plugins
        show = general, search
        elements {
            general {
                iconIdentifier = content-plugin
                title = General Plugin
                tt_content_defValues.CType = list
            }

            search {
                iconIdentifier = content-elements-searchform
                title = Indexed Search
                tt_content_defValues {
                    CType = list
                    list_type = indexedsearch_pi2
                }
            }
        }
    }
}

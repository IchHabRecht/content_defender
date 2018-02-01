mod.web_layout.BackendLayouts.default {
    title = Default
    config.backend_layout {
        colCount = 3
        rowCount = 3
        rows {
            1 {
                columns {
                    1 {
                        name = Border (all)
                        colPos = 3
                        colspan = 5
                    }


                    2 {
                        name = Disabled
                        colPos =
                    }
                }
            }

            2 {
                columns {
                    1 {
                        name = Normal (header, textmedia, list[-indexed_search_pi2])
                        colPos = 0
                        colspan = 6
                        allowed {
                            CType = header, textmedia, list
                        }
                        disallowed {
                            list_type = indexed_search_pi2
                        }
                    }
                }
            }

            3 {
                columns {
                    1 {
                        name = Footer1 (no bullets)
                        colPos = 10
                        disallowed {
                            CType = bullets
                        }
                    }

                    2 {
                        name = Footer2 (bullets)
                        colPos = 11
                        allowed {
                            CType = bullets
                        }
                    }

                    3 {
                        name = Footer3 (all)
                        colPos = 12
                    }
                }
            }
        }
    }
}

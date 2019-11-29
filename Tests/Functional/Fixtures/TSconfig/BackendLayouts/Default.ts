mod.web_layout.BackendLayouts.default {
    title = Default
    config.backend_layout {
        colCount = 6
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
                        colspan = 1
                    }
                }
            }

            2 {
                columns {
                    1 {
                        name = Disabled without colPos
                        colspan = 1
                    }

                    2 {
                        name = Normal (header, textmedia, list[-indexed_search_pi2])
                        colPos = 0
                        colspan = 5
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
                        colspan = 2
                        disallowed {
                            CType = bullets
                        }
                    }

                    2 {
                        name = Footer2 (bullets)
                        colPos = 11
                        colspan = 2
                        allowed {
                            CType = bullets
                        }
                    }

                    3 {
                        name = Footer3 (all, tx_simpleselectboxsingle=5)
                        colPos = 12
                        colspan = 2
                        allowed {
                            tx_simpleselectboxsingle = 5
                        }
                    }
                }
            }
        }
    }
}

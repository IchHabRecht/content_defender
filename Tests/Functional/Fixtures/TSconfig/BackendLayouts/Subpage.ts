mod.web_layout.BackendLayouts.subpage {
    title = Subpage
    config.backend_layout {
        colCount = 6
        rowCount = 3
        rows {
            1 {
                columns {
                    1 {
                        name = Left (maxitems = 3)
                        colPos = 0
                        colspan = 3
                        maxitems = 3
                    }

                    2 {
                        name = Right (maxitems = 1)
                        colPos = 3
                        colspan = 3
                        maxitems = 1
                    }
                }
            }

            2 {
                columns {
                    1 {
                        name = Footer1 (header, list[indexedsearch_pi2])
                        colPos = 10
                        colspan = 2
                        allowed {
                            CType = header, list
                            list_type = indexedsearch_pi2
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
                        name = Footer3 (all)
                        colPos = 12
                        colspan = 2
                    }
                }
            }
        }
    }
}

var MenuPage = require("./pages/page_menu.js");
var menu = new MenuPage(casper);
var names = [["Home", "Snibston"], 
            ["Century", "Century Theatre"], 
            ["What", "What"], 
            ["Hire", "Hire Snibston"]];

casper.test.begin('Test desktop menu at ' + casper.cli.options.baseUrl, {
    setUp: function (test) {
        casper.options.viewportSize = { width: 1024, height: 768 };
    },

    test: function (test) {
        casper.start()
            .thenOpen(casper.cli.options.baseUrl)
            .then(function () {
                menu.clickAcceptCookies();
            })
            .eachThen(names, function (name) {
                menu.clickItemAndCheckHeader(name.data[0], name.data[1]);
            })
            .run(function () { test.done(); });
    },

    tearDown: function(test) {
        // nothing
    }
});

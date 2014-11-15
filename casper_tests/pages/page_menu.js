var require = patchRequire(require);
var x = require('casper').selectXPath;

function MenuPage(casper) {
    this.casper = casper;
};

MenuPage.prototype.clickItemAndCheckHeader = function(itemName, title){
    casper.waitForSelector("#masthead", function() {
        casper.click(x("//a[starts-with(.,'" + itemName + "')]"))
    });
    casper.waitForSelector("header h1", function () {
        var header = casper.fetchText("header h1");
        casper.test.assertSelectorHasText("header h1", title);
    });
};

MenuPage.prototype.clickAcceptCookies = function() {
    casper.waitForSelector("#masthead", function () {
        casper.click(x("//a[starts-with(.,'ACCEPT COOKIES')]"))
    });
};

module.exports = MenuPage;
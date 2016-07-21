// make checker global 
//
//window.HTMLCS = HTMLCS;
//window.HTMLCS_RUNNER = HTMLCS_RUNNER;

var snifrunner = new function() {
    this.run = function(standard, callback) {
        var self = this;

        return HTMLCS_RUNNER.run(standard);
    }
}

return snifrunner.run('WCAG2AA');

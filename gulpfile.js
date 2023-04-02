var gulp = require('gulp');
var browserSync = require('browser-sync');
var php = require("gulp-connect-php");

function connectsync() {
    php.server({
    	port:8000,
    	keepalive:true,
    	base:"."
    }, function(){
    	browserSync({
    		proxy:'127.0.0.1:8000'
    	});
    });
}

function browserSyncReload(){
	browserSync.reload();
	
}

function watchFiles(){
	gulp.watch("./*.html").on("change", browserSyncReload);
	gulp.watch("./*.php").on("change", browserSyncReload);
	gulp.watch("./css/*.css").on("change", browserSyncReload);
	gulp.watch("./scripts/*.js").on("change", browserSyncReload);
}
const watch = gulp.parallel([watchFiles, connectsync]);
exports.default = watch;

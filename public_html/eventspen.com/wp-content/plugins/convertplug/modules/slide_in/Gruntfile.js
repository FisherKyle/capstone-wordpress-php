module.exports = function(grunt) {
	var cssPath = 'assets/demos';
	grunt.initConfig({
		uglify: {
			js: {
				files: {
					'assets/js/slide_in.min.js': [
						'!assets/js/admin.min.js',
						'assets/js/slide_in.common.js',
						'assets/js/slide_in.js',
						'assets/js/mailer.js',
					]
				}
			}
		},
		cssmin: {
			css: {
				files: {
					'assets/css/slide_in.min.css': [
						'!assets/css/slide_in.min.css',
						'assets/css/slide_in.css',
						'assets/css/slide_in-grid.css',
						'../assets/css/animate.css',
						'../assets/css/cp-social-media-style.css',
						'../assets/css/social-icon-css.css',
						'../assets/css/convertplug.css',
					]
				}
			}
		},
		watch: {
            scripts: {
                files: [cssPath + '/**/*.{css,png}'],
                tasks: ['default','uglify:js','cssmin:css','customMinify','optimizeImg']
            }
        }
	});
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-imagemin');
	grunt.registerTask('default', ['uglify:js','cssmin:css','customMinify','optimizeImg']);
	
	grunt.registerTask('customMinify', 'minify all files', function() {
        
        grunt.file.expand(cssPath + '/**/*.css').forEach(function(dir) {
            var folderArr = dir.split("/");
			var file = folderArr[folderArr.length - 1];
            var fileName = folderArr[folderArr.length - 2];
			var newDir = dir.replace( file, "" );
			
			// get the current cssmin config
            var minify = grunt.config.get('cssmin') || {};
			var cssFile = newDir + fileName + '.css';
			var minFile = newDir + fileName + '.min.css';
			
			// log the directory, main css and minified css path
//			grunt.log.writeln('Directory: ' + newDir);
//			grunt.log.writeln('File Name: ' + cssFile);
//			grunt.log.writeln('Min File: ' + minFile);
 
            minify[fileName] = {
				src: cssFile,
                dest: minFile
			};
			
			// save the new cssmin config
            grunt.config.set('cssmin', minify);
        });
        
        // finally run the concat file
        grunt.task.run('cssmin');
    });
	
	// optimize images
	grunt.registerTask('optimizeImg', 'minify all files', function() {
        
        grunt.file.expand(cssPath + '/**/*.png').forEach(function(dir) {
            var folderArr = dir.split("/");
			var file = folderArr[folderArr.length - 1];
            var fileName = folderArr[folderArr.length - 2];
			var newDir = dir.replace( file, "" );
			
			// get the current cssmin config
            var minify = grunt.config.get('imagemin') || {};
			var imgFile = newDir + fileName + '.png';
			var minFile = newDir + fileName + '.min.png';
			 
            minify[fileName] = {
				src: imgFile,
				dest: imgFile
			};
			
			// save the new cssmin config
            grunt.config.set('imagemin', minify);
        });
        
        // finally run the concat file
        grunt.task.run('imagemin');
    });
};
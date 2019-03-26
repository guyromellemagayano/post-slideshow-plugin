const paths = {};

// Public folder naming conventions
paths.cssFolderName = "css";
paths.imagesFolderName = "img";
paths.jsFolderName = "js";
paths.nodeFolderName = "node_modules";
paths.distFolderName = "dist";
paths.scssFolderName = "scss";
paths.sourceFolderName = "src";

// Public folder naming conventions
paths.publicFolderName = "public";

// Admin folder naming conventions
paths.adminFolderName = "admin";

// Directory locations
paths.adminDir = `${paths.adminFolderName}/`;
paths.distDir = `${paths.distFolderName}/`;
paths.nodeDir = `${paths.nodeFolderName}/`;
paths.publicDir = `${paths.publicFolderName}/`;
paths.sourceDir = `${paths.sourceFolderName}/`;

// Public source asset file locations
paths.public_imageSourceDir = `${paths.publicDir + paths.sourceDir + paths.imagesFolderName}/`;
paths.public_jsSourceDir = `${paths.publicDir + paths.sourceDir + paths.jsFolderName}/`;
paths.public_scssSourceDir = `${paths.publicDir + paths.sourceDir + paths.scssFolderName}/`;

// Admin source asset file locations
paths.admin_imageSourceDir = `${paths.adminDir + paths.sourceDir + paths.imagesFolderName}/`;
paths.admin_jsSourceDir = `${paths.adminDir + paths.sourceDir + paths.jsFolderName}/`;
paths.admin_scssSourceDir = `${paths.adminDir + paths.sourceDir + paths.scssFolderName}/`;

// Public compiled file locations
paths.public_cssPublicDir = `${paths.publicDir + paths.distDir + paths.cssFolderName}/`;
paths.public_imagePublicDir = `${paths.publicDir + paths.distDir + paths.imagesFolderName}/`;
paths.public_jsPublicDir = `${paths.publicDir + paths.distDir + paths.jsFolderName}/`;

// Admin compiled file locations
paths.admin_cssPublicDir = `${paths.adminDir + paths.distDir + paths.cssFolderName}/`;
paths.admin_imagePublicDir = `${paths.adminDir + paths.distDir + paths.imagesFolderName}/`;
paths.admin_jsPublicDir = `${paths.adminDir + paths.distDir + paths.jsFolderName}/`;

// Global patterns by file type
paths.imagePattern = "*.+(jpg|JPG|jpeg|JPEG|png|PNG|svg|SVG|gif|GIF|webp|WEBP|tif|TIF|eps|EPS)";
paths.jsPattern = "/*.js";
paths.scssPattern = "/**/*.scss";

// Public file globals
paths.public_imageFilesGlob = paths.public_imageSourceDir + paths.imagePattern;
paths.public_jsFilesGlob = paths.public_jsSourceDir + paths.jsPattern;
paths.public_scssFilesGlob = paths.public_scssSourceDir + paths.scssPattern;

// Admin file globals
paths.admin_imageFilesGlob = paths.admin_imageSourceDir + paths.imagePattern;
paths.admin_jsFilesGlob = paths.admin_jsSourceDir + paths.jsPattern;
paths.admin_scssFilesGlob = paths.admin_scssSourceDir + paths.scssPattern;

export default paths;

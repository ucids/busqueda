<?php
function searchFilesRecursive($dir, $search_term)
{
    $matching_files = array();
    $matching_files_with_v2 = array();

    $files = glob($dir . '*' . $search_term . '*', GLOB_BRACE);

    foreach ($files as $file) {
        if (is_file($file)) {
            if (strpos($file, '-V3') !== false) {
                $matching_files_with_v2[] = $file;
            } else {
                $matching_files[] = $file;
            }
        } elseif (is_dir($file)) {
            $results = searchFilesRecursive($file . '/', $search_term);

            // Agregar los resultados de búsqueda de los subdirectorios
            $matching_files = array_merge($matching_files, $results['matching_files']);
            $matching_files_with_v2 = array_merge($matching_files_with_v2, $results['matching_files_with_v2']);
        }
    }

    return array(
        'matching_files' => $matching_files,
        'matching_files_with_v2' => $matching_files_with_v2
    );
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search_term'])) {
        $search_term = $_POST['search_term'];
        $search_term = strtoupper($search_term);
        $directory = '/mserver/BUSCADOR/';

        // Escapar el texto de búsqueda para evitar problemas con caracteres especiales
        $escaped_search_term = preg_quote($search_term, '/');

        // Obtener una lista de archivos que coincidan con el término de búsqueda
        $results = searchFilesRecursive($directory, $escaped_search_term);

        $matching_files = $results['matching_files'];
        $matching_files_with_v2 = $results['matching_files_with_v2'];
    }
}
function getFileNameWithoutExtension($file_path) {
    // Obtenemos el último nombre después del último '/'
    $file_name = basename($file_path);
    
    // Eliminamos la extensión '.pdf'
    $file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);
    
    return $file_name_without_extension;
}
?>

<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
    <?php include 'modules/head.php'; ?>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                <?php include 'modules/header.php'; ?>
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<!--begin::Wrapper container-->
					<div class="app-container container-xxl d-flex flex-row-fluid">
						<!--begin::Sidebar-->
                        <?php include 'modules/side.php'; ?>
						<!--end::Sidebar-->
						<!--begin::Main-->
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<!--begin::Content wrapper-->
							<div class="d-flex flex-column flex-column-fluid">
								<!--begin::Toolbar-->
                                <?php include 'modules/toolbar.php'?>
								<!--end::Toolbar-->
								<!--begin::Content-->
								<div id="kt_app_content" class="app-content flex-column-fluid">
									<!--begin::Navbar-->
                                    <?php include 'modules/user.php'; ?>
									<!--end::Navbar-->
									<!--begin::Documents toolbar-->
									<div class="d-flex flex-wrap flex-stack mb-6">
										<!--begin::Title-->
										<h3 class="fw-bold my-2">Manufacturing </h3>
										<!-- <span class="fs-6 text-gray-400 fw-semibold ms-1">100+ resources</span></h3> -->
										<!--end::Title-->
									</div>
									<!--end::Documents toolbar-->
                                    <? if (!empty($matching_files)) { ?>
                                        <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                                        <?php foreach ($matching_files as $file) {
                                            $file_name = basename($file); ?>
                                        <!--begin::Col-->
                                        <div class="col-md-6 col-lg-4 col-xl-3">
											<!--begin::Card-->
											<div class="card h-100">
												<!--begin::Card body-->
												<div class="card-body d-flex justify-content-center text-center flex-column p-8">
													<!--begin::Name-->
                                                    <a href="pdf.php?file=<?php echo urlencode($file); ?>" target="_blank">
														<!--begin::Image-->
														<div class="symbol symbol-60px mb-5">
															<img src="assets/media/svg/files/pdf.svg" class="theme-light-show" alt="" />
															<img src="assets/media/svg/files/pdf-dark.svg" class="theme-dark-show" alt="" />
														</div>
														<!--end::Image-->
														<!--begin::Title-->
														<div class="fs-5 fw-bold mb-2"><?echo getFileNameWithoutExtension($file);?></div>
														<!--end::Title-->
													</a>
													<!--end::Name-->
													<!--begin::Description-->
													<!-- <div class="fs-7 fw-semibold text-gray-400">3 days ago</div> -->
													<!--end::Description-->
												</div>
												<!--end::Card body-->
											</div>
											<!--end::Card-->
										</div>
										<!--end::Col-->
                                        <?} ?>
									</div>
                                    <?} ?>
									<!--begin::Row-->
									
									<!--end:Row-->
									<!--begin::Row-->
									<!--begin::Documents toolbar-->
                                    <div class="d-flex flex-wrap flex-stack mb-6">
										<!--begin::Title-->
										<!-- <span class="fs-6 text-gray-400 fw-semibold ms-1">100+ resources</span></h3> -->
										<!--end::Title-->
									</div>
                                    <? if (!empty($matching_files_with_v2)) { ?>
										<h3 class="fw-bold my-2">Ayuda Visual - V3 </h3>

                                        <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                                        <?php foreach ($matching_files_with_v2 as $file) {
                                            $file_name = basename($file); ?>
                                        <!--begin::Col-->
                                        <div class="col-md-6 col-lg-4 col-xl-3">
											<!--begin::Card-->
											<div class="card h-100">
												<!--begin::Card body-->
												<div class="card-body d-flex justify-content-center text-center flex-column p-8">
													<!--begin::Name-->
                                                    <a href="pdf.php?file=<?php echo urlencode($file); ?>" target="_blank">

														<!--begin::Image-->
														<div class="symbol symbol-60px mb-5">
															<img src="assets/media/svg/files/pdf.svg" class="theme-light-show" alt="" />
															<img src="assets/media/svg/files/pdf-dark.svg" class="theme-dark-show" alt="" />
														</div>
														<!--end::Image-->
														<!--begin::Title-->
														<div class="fs-5 fw-bold mb-2"><?echo getFileNameWithoutExtension($file);?></div>
														<!--end::Title-->
													</a>
													<!--end::Name-->
													<!--begin::Description-->
													<div class="fs-7 fw-semibold text-gray-400">Version 3</div>
													<!--end::Description-->
												</div>
												<!--end::Card body-->
											</div>
											<!--end::Card-->
										</div>
										<!--end::Col-->
                                        <?} ?>
									</div>
                                    <?} ?>
									<!--end:Row-->
								</div>
								<!--end::Content-->
							</div>
							<!--end::Content wrapper-->
							<!--begin::Footer-->
                            <?php include 'modules/footer.php'; ?>
							<!--end::Footer-->
						</div>
						<!--end:::Main-->
					</div>
					<!--end::Wrapper container-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::App-->
		<!--begin::Scrolltop-->
        <?php include 'modules/scripts.php';?>
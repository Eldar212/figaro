<div class="left_mnu_personal">

    <?

    $arrs_mnu = array(
        array(
            "path" => "/personal/private/",
            "name" => "Мои данные",
            "icon" =>
                '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.168 16.3C4.744 14.32 5.824 13.6 7.066 13.6H12.934C14.176 13.6 15.256 14.32 15.832 16.3M12.7 8.2C12.7 9.69117 11.4912 10.9 10 10.9C8.50883 10.9 7.3 9.69117 7.3 8.2C7.3 6.70883 8.50883 5.5 10 5.5C11.4912 5.5 12.7 6.70883 12.7 8.2ZM19 10C19 14.9706 14.9706 19 10 19C5.02944 19 1 14.9706 1 10C1 5.02944 5.02944 1 10 1C14.9706 1 19 5.02944 19 10Z" stroke="#111111" stroke-width="1.5" stroke-linecap="square"/>
                </svg>'
        ),
        array(
            "path" => "/personal/favorites/",
            "name" => "Избранное",
            "icon" =>
                '<svg width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.99607 1C4.6629 1 3.40613 1.521 2.46366 2.4633C1.51741 3.40938 0.996206 4.66605 1.00002 6.00305C1.00004 7.34009 1.52556 8.59334 2.47172 9.53933L10.0057 16L17.5325 9.55406C18.4788 8.60799 18.9999 7.35139 19 6.0145C19.0038 4.67756 18.4865 3.42084 17.5401 2.47469C16.5938 1.52856 15.3408 1.01139 14.0039 1.01139C12.667 1.01139 11.4102 1.53239 10.4639 2.47848L10.0057 2.93663L9.53988 2.47089C8.59366 1.52483 7.3331 1 5.99607 1Z" stroke="#111111" stroke-width="1.5"/>
                </svg>'
        ),
        array(
            "path" => "/personal/orders/?filter_history=Y",
            "name" => "Мои Заказы",
            "icon" =>
                '<svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.38 8.44543L16.3804 8.44844L17.5849 16.5516C17.628 16.9424 17.5785 17.3377 17.4393 17.7094C17.2994 18.0833 17.0717 18.4251 16.7712 18.7088L16.7688 18.7111C16.1726 19.2833 15.3509 19.6 14.4382 19.6H3.57592C2.62128 19.6 1.73193 19.2363 1.13517 18.6373L1.13483 18.637C0.541167 18.0425 0.300594 17.2832 0.43738 16.5532L0.438862 16.5453L0.440025 16.5373L1.63248 8.37069C1.73552 7.76063 2.35586 7.24948 3.21375 7.24948H3.89645H4.29645V6.84948V4.21011C4.29645 3.33482 4.52068 2.62005 4.96825 2.00553L4.96838 2.00562L4.97488 1.99614C5.36447 1.42757 6.72772 0.4 9.21231 0.4C10.3524 0.4 11.1519 0.675456 11.7234 1.00732C12.299 1.34155 12.6621 1.74292 12.9269 2.03749L12.9274 2.03795C13.5147 2.68904 13.811 3.33745 13.811 4.21011V6.85026V7.25026H14.211H14.8004C15.6705 7.25026 16.2848 7.77021 16.38 8.44543ZM3.19766 8.03904C3.24691 8.03862 3.31772 8.03823 3.40795 8.03785C3.40574 7.88752 3.35752 7.73943 3.27201 7.67202L3.19766 8.03904ZM4.24566 11.0218L4.24494 8.43567C4.38599 8.43541 4.53796 8.43516 4.69949 8.43493V8.58813H4.69936L4.69962 8.59824L4.70032 8.62613V11.2149C4.70032 11.2577 4.6825 11.3039 4.64225 11.3419C4.60119 11.3806 4.54069 11.4061 4.47299 11.4061C4.40529 11.4061 4.3448 11.3806 4.30374 11.3419C4.26348 11.3039 4.24566 11.2577 4.24566 11.2149V11.0219V11.0218ZM13.3546 11.2149V8.43041L13.8094 8.43037L13.8101 11.0219V11.022V11.2149C13.8101 11.2578 13.7923 11.3041 13.7519 11.3421C13.7108 11.3809 13.6502 11.4065 13.5824 11.4065C13.5146 11.4065 13.454 11.3809 13.4128 11.3421C13.3725 11.3041 13.3546 11.2578 13.3546 11.2149ZM14.7759 7.63029L14.6092 7.63029V7.63028H14.2093H12.9546H12.5546V7.63053C11.5237 7.6307 10.2632 7.63102 9.00327 7.63156C7.76143 7.6321 6.52004 7.63286 5.49949 7.63394V7.63028H5.09949H3.84483H3.44472L3.44472 7.6377C3.36294 7.63802 3.29495 7.63835 3.24217 7.6387C3.19937 7.63898 3.16568 7.63928 3.14252 7.6396L3.11218 7.64018C3.11005 7.64024 3.10607 7.64036 3.10123 7.64058C2.63996 7.64663 2.19511 7.92884 2.10476 8.40534L2.10326 8.41323L2.10209 8.42118L0.890279 16.5931C0.759645 17.2842 1.00375 17.9628 1.50373 18.4547L1.50389 18.4548C2.01379 18.9559 2.73639 19.2302 3.48434 19.2302H14.5398C15.253 19.2302 15.9227 18.9757 16.4179 18.5122C16.6815 18.2676 16.8845 17.9679 17.0097 17.635C17.1351 17.3017 17.1787 16.9449 17.1366 16.5921L17.1368 16.5921L17.135 16.5804L15.922 8.48102L15.9218 8.47987C15.8846 8.23593 15.7685 8.01725 15.58 7.8609C15.3924 7.70529 15.1561 7.63028 14.9086 7.63028V8.03028C14.9086 7.63028 14.9086 7.63028 14.9085 7.63028H14.9081H14.9065H14.9001L14.8747 7.63029L14.7759 7.63029ZM4.64787 4.21011V4.61011H4.66119V6.84006V7.24271L5.06383 7.24005C5.61844 7.2364 6.1386 7.23289 6.62653 7.2296C10.1256 7.20599 11.9675 7.19356 12.9735 7.21953L13.3838 7.23012V6.81966V4.38067C13.487 2.34797 11.5919 0.854997 9.31786 0.78024L9.31787 0.780024H9.30472H8.91508C6.96038 0.780024 5.91466 1.4706 5.37227 2.13301C4.87412 2.71447 4.64787 3.40989 4.64787 4.21011Z" stroke="#111111" stroke-width="0.8"/>
                </svg>'
        )
    );

    ?>

    <?foreach ($arrs_mnu as $arr_mnu) :?>

        <?

        if ($_SERVER['REQUEST_URI'] == $arr_mnu['path']) {
            $active_menu = 'act_el_mnu';
        } else {
            $active_menu = '';
        }

	    if ($_SERVER['SCRIPT_NAME'] == '/personal/index.php' && $arr_mnu['path'] == '/personal/private/') {
		    $active_menu = 'mob_act_el_mnu';
	    }
        ?>
        <div class="el_mnu_personal <?= $active_menu ?>">
            <a href="<?=$arr_mnu['path']?>">
                <div class="flex">
                    <div class="flex">
                            <span class="sale-personal-section-index-block-ico">
                                <?=$arr_mnu['icon']?>
                            </span>
                        <h2 class="sale-personal-section-index-block-name">
                            <?=$arr_mnu['name']?>
                        </h2>
                    </div>
                    <div class="arrow">
                        <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 13L7 7L1 1" stroke="#111111"/>
                        </svg>
                    </div>
                </div>

            </a>
        </div>
    <?endforeach;?>

</div>
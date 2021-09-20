<?php
if (!empty($patchworks->enable_negative_spacing)) {
    if ($patchworks->enable_negative_spacing == true) {
        ?>
        <div class="inputContainer">
            <label>Negative Spacing (Top)</label>
            <div class="inputWrapper">
                <select name="negmargin">
                    <?php
                    if (empty($patchworks->negative_spacing)) {
                        ?>
                        <option value="">None</option>
                        <option value="tinyneg">Tiny</option>
                        <option value="neg">Small</option>
                        <option value="neg1">Medium</option>
                        <option value="neg2">Large</option>
                        <option value="neg3">Extra Large</option>
                        <?php
                    } else {
                        echo '<option value="">None</option>';
                        foreach ($patchworks->negative_spacing as $negative_spacing) {
                            echo '<option value="' . $negative_spacing['class'] . '">' . $negative_spacing['name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <?php
    }
}
if (!empty($patchworks->enable_spacing_options)) {
    if ($patchworks->enable_spacing_options == true) {
        ?>
        <div class="cms-spacing">
            <div class="inputContainer">
                <label>Adjust Spacing</label>
                <div class="inputWrapper">
                    <select name="spacing">
                        <?php
                        if (empty($patchworks->spacing_options)) {
                            ?>
                            <option value="">Default</option>
                            <option value="tiny">Tiny</option>
                            <option value="small">Small</option>
                            <option value="medium">Medium</option>
                            <option value="large">Large</option>
                            <?php
                        } else {
                            echo '<option value="">Default</option>';
                            foreach ($patchworks->spacing_options as $spacing) {
                                echo '<option value="' . $spacing['class'] . '">' . $spacing['name'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <?php
    }
}

if (!empty($patchworks->enable_spacing_adjustments)) {
    if ($patchworks->enable_spacing_adjustments == true) {
        ?>
        <div class="inputContainer">
            <label>No Padding (Remove Top Gap)</label>
            <div class="inputWrapper">
                <input type="checkbox" name="nopadding" value="nopadding"/>
            </div>
        </div>
        <div class="inputContainer">
            <label>No Margin (Remove Bottom Gap)</label>
            <div class="inputWrapper">
                <input type="checkbox" name="nomargin" value="nomargin"/>
            </div>
        </div>
        <?php
    }
}

if (!empty($patchworks->enable_extra_spacing_adjustments)) {
    if ($patchworks->enable_extra_spacing_adjustments == true) {
        ?>
        <div class="inputContainer">
            <label>Extra Padding (Extra Bottom Gap)</label>
            <div class="inputWrapper">
                <input type="checkbox" name="extrapadding" value="extrapadding"/>
            </div>
        </div>
        <div class="inputContainer">
            <label>Extra Margin (Extra Top Gap)</label>
            <div class="inputWrapper">
                <input type="checkbox" name="extramargin" value="extramargin"/>
            </div>
        </div>
        <?php
    }
}

if (!empty($patchworks->enable_theme)) {
    if ($patchworks->enable_theme == true) {
        ?>
        <div class="inputContainer">
            <label>Theme Options</label>

            <div class="inputWrapper">
                <select name="theme">
                    <?php
                    if (empty($patchworks->theme)) {
                        ?>
                        <option value="">Default Theme</option>
                        <option value="theme1">Theme1</option>
                        <option value="theme2">Theme2</option>
                        <option value="theme3">Theme3</option>
                        <option value="theme4">Theme4</option>
                        <option value="theme5">Theme5</option>
                        <?php
                    } else {
                        echo '<option value="">Default Theme</option>';
                        foreach ($patchworks->theme as $theme) {
                            echo '<option value="' . $theme['class'] . '">' . $theme['name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <?php
    }
}

if (!empty($patchworks->enable_indent)) {
    if ($patchworks->enable_indent == true) {
        ?>
        <div class="inputContainer">
            <label>Indent Options</label>
            <div class="inputWrapper">
                <select type="checkbox" name="indent">
                    <?php
                    if (empty($patchworks->indent)) {
                        ?>
                        <option value="">No Indent</option>
                        <option value="indent-10">Indent 10</option>
                        <option value="indent-20">Indent 20</option>
                        <option value="indent-30">Indent 30</option>
                        <?php
                    } else {
                        echo '<option value="">No Indent</option>';
                        foreach ($patchworks->indent as $indent) {
                            echo '<option value="' . $indent['class'] . '">' . $indent['name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <?php
    }
}
if (!empty($patchworks->enable_animations)) {
    if ($patchworks->enable_animations == true) {
        ?>
        <div class="inputContainer">
            <label>Transition Animations</label>
            <div class="inputWrapper">
                <select type="checkbox" name="animation">
                    <?php
                    if (empty($patchworks->enable_animations)) {
                        ?>
                        <option value="">No Animation</option>
                        <option value="animation-flip-in">Roll In</option>
                        <option value="animation-fade">Fade</option>
                        <?php
                    } else {
                        echo '<option value=""></option>';
                        foreach ($patchworks->animations as $animations) {
                            echo '<option value="' . $animations['class'] . '">' . $animations['name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <?php
    }
}
?>
<br>
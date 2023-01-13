<?php

class Users_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert($company_data, $employer_data, $employer_portal_data) {
        $this->db->insert('users', $company_data);
        $user_id                                                                = $this->db->insert_id();
        $employer_data["parent_sid"]                                            = $user_id;
        $this->db->insert('users', $employer_data);
        $emp_id                                                                 = $this->db->insert_id();
        $employer_portal_data["user_sid"]                                       = $user_id;
        $this->db->insert('portal_employer', $employer_portal_data);
        $CompanyName                                                            = $company_data['CompanyName'];
        $theme1                                                                 = "INSERT INTO `portal_themes` (`theme_status`, `theme_name`, `theme_image`, `user_sid`,  `body_bgcolor`, `heading_color`, `font_color`, `hf_bgcolor`, `title_color`, `f_bgcolor`, `pictures`) VALUES ('1', 'theme-1', 'theme_1_img_preview.jpg', '" . $user_id . "', '#000000', '', '#0099ff', '#b3c211', '#b3c211', '#0e0e0e', 'theme_1-EaOtK.jpg')";
        $theme2                                                                 = "INSERT INTO `portal_themes` (`sid`, `theme_status`, `theme_name`, `theme_image`, `user_sid`,  `body_bgcolor`, `heading_color`, `font_color`, `hf_bgcolor`, `title_color`, `f_bgcolor`, `pictures`) VALUES (NULL, '0', 'theme-2', 'theme_2_img_preview.jpg', '" . $user_id . "',  '#00cccc', '#b3c211', '#00000', '#00b6b6', '#0099ff', '', 'theme_2-X6glS.jpg')";
//        $theme3                                                                 = "INSERT INTO `portal_themes` (`sid`, `theme_status`, `theme_name`, `theme_image`, `user_sid`,  `body_bgcolor`, `heading_color`, `font_color`, `hf_bgcolor`, `title_color`, `f_bgcolor`, `pictures`) VALUES (NULL, '0', 'theme-3', 'theme_3_img_preview.jpg', '" . $user_id . "',  '#b39ddb', '#a087ce', '#3f51b5', '#b3c211', '#0099ff', '#ffffff', 'theme_3-A8KRT.jpg')";
        $theme3                                                                 = "INSERT INTO `portal_themes` (`sid`, `theme_status`, `theme_name`, `theme_image`, `user_sid`,  `body_bgcolor`, `heading_color`, `font_color`, `hf_bgcolor`, `title_color`, `f_bgcolor`, `pictures`) VALUES (NULL, '0', 'theme-3', 'theme_3_img_preview.jpg', '" . $user_id . "',  'blue', 'blue', '#3f51b5', '#b3c211', '#0099ff', '#ffffff', 'theme_3-A8KRT.jpg')";
        $theme4                                                                 = "INSERT INTO `portal_themes` (`sid`, `theme_status`, `theme_name`, `theme_image`, `user_sid`, `portal_sid`, `body_bgcolor`, `heading_color`, `font_color`, `hf_bgcolor`, `title_color`, `f_bgcolor`, `pictures`, `is_paid`, `purchased`, `purchase_date`, `expiry_date`) VALUES (null, 0, 'theme-4', 'theme_4_img_preview.jpg', " . $user_id . ", 0, '#ffffff', '#000000', '#0099ff', '#43BD00', '#009601', '#0e0e0e', 'theme_4_default_image-RPBc4.jpg', b'1', 0, NULL, NULL)";
        //Theme 4 Pages
        //$theme4_page1                                                           = "INSERT INTO `portal_themes_pages` (`sid`, `company_id`, `theme_name`, `page_unique_name`, `page_name`, `page_title`, `page_content`, `page_banner`, `page_status`, `page_banner_status`, `page_youtube_video`, `page_youtube_video_status`, `page_default_banner`, `video_location`) VALUES (null, " . $user_id . ", 'theme-4', 'page_01', 'employee_benefits', 'Employee Benefits', '&lt;div class=&quot;job-description-text&quot;&gt;&lt;h1&gt;Benefit Plan&lt;/h1&gt;&lt;p&gt;Our benefit plan offers a wide range of competitive benefits for you and your family, such as health, dental, long term disability and Life Insurance.&lt;/p&gt;&lt;h1&gt;The Wellness Program&lt;/h1&gt;&lt;p&gt;To help you manage your busy career and personal life, we offer wellness programs, tools and resources to help you &amp;amp; your family achieve a healthy balance and handle the challenges that life presents.&lt;/p&gt;&lt;p&gt;Employee Assistance Program &amp;ndash; free confidential counselling and support services to help you and your immediate family with personal and professional issues.&lt;/p&gt;&lt;p&gt;Mental Health &amp;ndash; access support information, online tools and professional resources.&lt;/p&gt;&lt;p&gt;Most of our dealership locations are outfitted with a state-of-the-art gym completed with exercise and weight lifting equipment.&lt;/p&gt;&lt;h1&gt;Vacation&lt;/h1&gt;&lt;p&gt;All employees are entitled to a generous vacation plan. Employees earn more time the longer they remain with Jim Butler Maserati, topping at 6-weeks&lt;/p&gt;&lt;h1&gt;Corporate Partners Program&lt;/h1&gt;&lt;p&gt;Jim Butler Maserati has made partnerships with organizations to provide our employees with discounted rates on such things as auto/home insurance, amusement parks, appliances, and other merchandise.&lt;/p&gt;&lt;h1&gt;Retirement Savings&lt;/h1&gt;&lt;p&gt;We offer a comprehensive RSP with a matching DPSP savings plan as well as TFSA.&lt;/p&gt;&lt;h1&gt;Employee, Friends and Family Purchase Program&lt;/h1&gt;&lt;p&gt;We encourage our employee&amp;#39;s, their friends and families to love the products they sell, service, etc. We have offer great employee discounts on vehicle purchases, service labour and parts. For vehicle inquiries for employee, friends and family.&lt;/p&gt;&lt;p&gt;Our benefit plan offers a wide range of competitive benefits for you and your family, such as health, dental, long term disability and Life Insurance.&lt;/p&gt;&lt;/div&gt;', '3-LL8dc.jpg', '1', '1', 'STRPsW6IY8k', '0', 'banner-2-lIO5m.jpg', 'top');";
        //$theme4_page1                                                           = "INSERT INTO `portal_themes_pages` (`sid`, `company_id`, `theme_name`, `page_unique_name`, `page_name`, `page_title`, `page_content`, `page_banner`, `page_status`, `page_banner_status`, `page_youtube_video`, `page_youtube_video_status`, `page_default_banner`, `video_location`) VALUES (null, " . $user_id . ", 'theme-4', 'page_01', 'employee_benefits', 'Employee Benefits', '&lt;div class=&quot;job-description-text&quot;&gt;&lt;h1&gt;Benefit Plan&lt;/h1&gt;&lt;p&gt;Our benefit plan offers a wide range of competitive benefits for you and your family, such as health, dental, long term disability and Life Insurance.&lt;/p&gt;&lt;h1&gt;The Wellness Program&lt;/h1&gt;&lt;p&gt;To help you manage your busy career and personal life, we offer wellness programs, tools and resources to help you &amp;amp; your family achieve a healthy balance and handle the challenges that life presents.&lt;/p&gt;&lt;p&gt;Employee Assistance Program &amp;ndash; free confidential counselling and support services to help you and your immediate family with personal and professional issues.&lt;/p&gt;&lt;p&gt;Mental Health &amp;ndash; access support information, online tools and professional resources.&lt;/p&gt;&lt;p&gt;Most of our dealership locations are outfitted with a state-of-the-art gym completed with exercise and weight lifting equipment.&lt;/p&gt;&lt;h1&gt;Vacation&lt;/h1&gt;&lt;p&gt;All employees are entitled to a generous vacation plan. Employees earn more time the longer they remain with {{company_name}}, topping at 6-weeks&lt;/p&gt;&lt;h1&gt;Corporate Partners Program&lt;/h1&gt;&lt;p&gt;{{company_name}} has made partnerships with organizations to provide our employees with discounted rates on such things as auto/home insurance, amusement parks, appliances, and other merchandise.&lt;/p&gt;&lt;h1&gt;Retirement Savings&lt;/h1&gt;&lt;p&gt;We offer a comprehensive RSP with a matching DPSP savings plan as well as TFSA.&lt;/p&gt;&lt;h1&gt;Employee, Friends and Family Purchase Program&lt;/h1&gt;&lt;p&gt;We encourage our employee&amp;#39;s, their friends and families to love the products they sell, service, etc. We have offer great employee discounts on vehicle purchases, service labour and parts. For vehicle inquiries for employee, friends and family.&lt;/p&gt;&lt;p&gt;Our benefit plan offers a wide range of competitive benefits for you and your family, such as health, dental, long term disability and Life Insurance.&lt;/p&gt;&lt;/div&gt;', 'default_banner_employee_benefits.jpg', '1', '1', 'STRPsW6IY8k', '0', 'default_banner_employee_benefits.jpg', 'top');";
        $theme4_page1                                                           = "INSERT INTO `portal_themes_pages` (`sid`, `company_id`, `theme_name`, `page_unique_name`, `page_name`, `page_title`, `page_content`, `page_banner`, `page_status`, `page_banner_status`, `page_youtube_video`, `page_youtube_video_status`, `page_default_banner`, `video_location`) VALUES (null, " . $user_id . ", 'theme-4', 'page_01', 'employee_benefits', 'Employee Benefits', '&lt;div class=&quot;job-description-text&quot;&gt;&lt;h1&gt;Benefit Plan&lt;/h1&gt;&lt;p&gt;Our benefit plan offers a wide range of competitive benefits for you and your family, such as health after 90 days of service and 401K.&lt;/p&gt;&lt;h1&gt;Vacation&lt;/h1&gt;&lt;p&gt;All employees are entitled to a generous vacation plan.&amp;nbsp;&lt;/p&gt;&lt;h1&gt;Employee, Friends and Family Purchase Program&lt;/h1&gt;&lt;p&gt;We encourage our employee&amp;#39;s, their friends and families to love the products they sell, service, etc. We have offer great employee discounts on vehicle purchases, service labour and parts. For vehicle inquiries for employee, friends and family.&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;/div&gt;', 'default_banner_employee_benefits.jpg', '1', '1', 'STRPsW6IY8k', '0', 'default_banner_employee_benefits.jpg', 'top');";

        //$theme4_page2                                                           = "INSERT INTO `portal_themes_pages` (`sid`, `company_id`, `theme_name`, `page_unique_name`, `page_name`, `page_title`, `page_content`, `page_banner`, `page_status`, `page_banner_status`, `page_youtube_video`, `page_youtube_video_status`, `page_default_banner`, `video_location`) VALUES (null, " . $user_id . ", 'theme-4', 'page_02', 'career_opportunities', 'Career Opportunities', '&lt;div id=&quot;lipsum&quot;&gt;&lt;p&gt;&lt;span style=&quot;font-size:20px&quot;&gt;&lt;strong&gt;Careers at $CompanyName &lt;/strong&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;At $CompanyName, we&amp;rsquo;re united by a passion for cars, and we&amp;rsquo;re as crazy about them as you are. We love driving the cars we sell, and we love the people that own and drive them. It&amp;rsquo;s all part of our ambition to be the best, most high-performance dealer.&lt;/p&gt;&lt;p&gt;Our continuous growth has created tremendous employment opportunities, as well as room for our current employees to grow within our organization. In addition to a fast-paced, exciting environment that rewards the entrepreneurial spirit, we offer excellent pay plans, benefits packages for full-time employees, professional training and development, and advancement opportunities throughout our dealer group.&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-size:20px&quot;&gt;&lt;strong&gt;Career Opportunities &lt;/strong&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;Sales &lt;/strong&gt;&lt;/p&gt;&lt;p&gt;We live, work, and sell in the 21st century. However, some things haven&amp;#39;t changed: treating people right is still the key to earning new, repeat, and referral business. With the training, education, tools and resources provided by $CompanyName, your potential is limitless.&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;Service&lt;/strong&gt;&lt;br /&gt;Our passion for cars doesn&amp;rsquo;t end with the sale. We maintain them, we customize them, we recondition them, and we even repair them after an accident. At $CompanyName we pride ourselves on the best-quality products and the best customer service in the industry.&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;Parts&lt;/strong&gt;&lt;br /&gt;We believe that our parts team needs to be as reliable as the parts they sell. Our parts experts learn the ins and outs of each model and year to provide an exceptional experience for customers, as well as providing exceptional support to the service team and technicians.&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;Finance&lt;/strong&gt;&lt;br /&gt;Finance is not just about the numbers. Many of our finance managers come from a sales or service background, understanding the true fundamentals of our business as they have grown within our organization.&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;Management&lt;/strong&gt;&lt;br /&gt;We strongly believe in growth from within. This means that most of our management team has come through the ranks from every department &amp;ndash; whether that be sales, service, or parts &amp;ndash; and knows the business inside-out. Our culture and beliefs are embedded in this group of leaders.&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;Technicians&lt;/strong&gt;&lt;br /&gt;In our shops you&amp;#39;ll find the best techs, the best equipment, the best benefits, and the best opportunity to grow in your trade &amp;ndash; all while working on some of the most exciting and exclusive vehicles around.&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;For Those Who Have Served&lt;/strong&gt;&lt;br /&gt;At $CompanyName, we are appreciative of those who serve our country and are committed to their successful transition back into the civilian workforce.&lt;/p&gt;&lt;p&gt;We are proud of those who have served in the military and are always excited to actively recruit, hire and train returning veterans.&lt;/p&gt;&lt;/div&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;', 'banner-2-7rUeR.jpg', '1', '1', '', '1', 'banner-2-7rUeR.jpg', 'top');";
        //$theme4_page2                                                           = "INSERT INTO `portal_themes_pages` (`sid`, `company_id`, `theme_name`, `page_unique_name`, `page_name`, `page_title`, `page_content`, `page_banner`, `page_status`, `page_banner_status`, `page_youtube_video`, `page_youtube_video_status`, `page_default_banner`, `video_location`) VALUES (null, " . $user_id . ", 'theme-4', 'page_02', 'career_opportunities', 'Career Opportunities', '&lt;div id=&quot;lipsum&quot;&gt;&lt;h1&gt;Careers at {{company_name}}&lt;/h1&gt;&lt;p&gt;At {{company_name}}, we&amp;rsquo;re united by a passion for cars, and we&amp;rsquo;re as crazy about them as you are. We love driving the cars we sell, and we love the people that own and drive them. It&amp;rsquo;s all part of our ambition to be the best, most high-performance dealer.&lt;/p&gt;&lt;p&gt;Our continuous growth has created tremendous employment opportunities, as well as room for our current employees to grow within our organization. In addition to a fast-paced, exciting environment that rewards the entrepreneurial spirit, we offer excellent pay plans, benefits packages for full-time employees, professional training and development, and advancement opportunities throughout our dealer group.&lt;/p&gt;&lt;hr /&gt;&lt;h1&gt;Career Opportunities&lt;/h1&gt;&lt;p&gt;&lt;iframe frameborder=&quot;0&quot; height=&quot;195&quot; src=&quot;https://www.youtube.com/embed/yz3wVgbH14o?rel=0&amp;amp;controls=0&amp;amp;showinfo=0&quot; width=&quot;346&quot;&gt;&lt;/iframe&gt;&lt;/p&gt;&lt;h1&gt;Sales&lt;/h1&gt;&lt;p&gt;We live, work, and sell in the 21st century. However, some things haven&amp;#39;t changed: treating people right is still the key to earning new, repeat, and referral business. With the training, education, tools and resources provided by {{company_name}}, your potential is limitless.&lt;/p&gt;&lt;p&gt;&lt;iframe frameborder=&quot;0&quot; height=&quot;195&quot; src=&quot;https://www.youtube.com/embed/MeGYVGW-nAI?rel=0&amp;amp;controls=0&amp;amp;showinfo=0&quot; width=&quot;346&quot;&gt;&lt;/iframe&gt;&lt;/p&gt;&lt;h1&gt;Service&lt;/h1&gt;&lt;p&gt;Our passion for cars doesn&amp;rsquo;t end with the sale. We maintain them, we customize them, we recondition them, and we even repair them after an accident. &amp;nbsp;At {{company_name}} we pride ourselves on the best-quality products and the best customer service in the industry.&lt;/p&gt;&lt;p&gt;&lt;iframe frameborder=&quot;0&quot; height=&quot;195&quot; src=&quot;https://www.youtube.com/embed/8alSG8rJkBg?rel=0&amp;amp;controls=0&amp;amp;showinfo=0&quot; width=&quot;346&quot;&gt;&lt;/iframe&gt;&lt;/p&gt;&lt;h1&gt;Parts&lt;/h1&gt;&lt;p&gt;We believe that our parts team needs to be as reliable as the parts they sell. Our parts experts learn the ins and outs of each model and year to provide an exceptional experience for customers, as well as providing exceptional support to the service team and technicians.&lt;/p&gt;&lt;h1&gt;Finance&lt;/h1&gt;&lt;p&gt;Finance is not just about the numbers. Many of our finance managers come from a sales or service background, understanding the true fundamentals of our business as they have grown within our organization.&amp;nbsp;&lt;/p&gt;&lt;h1&gt;Management&lt;/h1&gt;&lt;p&gt;We strongly believe in growth from within. This means that most of our management team has come through the ranks from every department &amp;ndash; whether that be sales, service, or parts &amp;ndash; and knows the business inside-out. Our culture and beliefs are embedded in this group of leaders.&lt;/p&gt;&lt;h1&gt;Technicians&lt;/h1&gt;&lt;p&gt;In our shops you&amp;#39;ll find the best techs, the best equipment, the best benefits, and the best opportunity to grow in your trade &amp;ndash; all while working on some of the most exciting and exclusive vehicles around.&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;h1&gt;For Those Who Have Served &amp;nbsp;&lt;/h1&gt;&lt;h1&gt;&lt;a href=&quot;https://www.google.com/imgres?imgurl=https%3A%2F%2Fc2.staticflickr.com%2F8%2F7687%2F17432053881_a14dce603e_b.jpg&amp;amp;imgrefurl=https%3A%2F%2Fwww.flickr.com%2Fphotos%2Falachuacounty%2F17432053881&amp;amp;docid=qpvXZcNHnFAj6M&amp;amp;tbnid=_lZ3Gj2wc_HGDM%3A&amp;amp;w=1024&amp;amp;h=704&amp;amp;bih=1263&amp;amp;biw=1708&amp;amp;ved=0ahUKEwjigYXW77_OAhVX9GMKHQgqAXAQMwg2KAMwAw&amp;amp;iact=mrc&amp;amp;uact=8&quot;&gt;&lt;img alt=&quot;&quot; src=&quot;https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTkcZ_q_8IRz3KrmMMAcpCrnmRAYRchb19P7T9RRebeeUSqvaaL&quot; /&gt;&lt;/a&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp;&lt;/h1&gt;&lt;p&gt;At {{company_name}}, we are appreciative of those who serve our country and are committed to their successful transition back into the civilian workforce.&lt;br /&gt;&lt;br /&gt;We are proud of those who have served in the military and are always excited to actively recruit, hire and train returning veterans.&lt;/p&gt;&lt;/div&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;', 'default_banner_career_opportunities.jpg', '1', '1', '', '1', 'default_banner_career_opportunities.jpg', 'top');";

        $page_content                                                           = '&lt;div id=&quot;lipsum&quot;&gt; &lt;h1&gt;Careers at {{company_name}}&lt;/h1&gt; &lt;p&gt;At {{company_name}}, we&amp;rsquo;re united by a passion for cars, and we&amp;rsquo;re as crazy about them as you are. We love driving the cars we sell, and we love the people that own and drive them. It&amp;rsquo;s all part of our ambition to be the best, most high-performance dealer.&lt;/p&gt; &lt;p&gt;Our continuous growth has created tremendous employment opportunities, as well as room for our current employees to grow within our organization. In addition to a fast-paced, exciting environment that rewards the entrepreneurial spirit, we offer excellent pay plans, benefits packages for full-time employees, professional training and development, and advancement opportunities throughout our dealer group.&lt;/p&gt; &lt;hr /&gt; &lt;h1&gt;Career Opportunities&lt;/h1&gt; &lt;h1&gt;Sales&lt;/h1&gt; &lt;p&gt;We live, work, and sell in the 21st century. However, some things haven&amp;#39;t changed: treating people right is still the key to earning new, repeat, and referral business. With the training, education, tools and resources provided by {{company_name}}, your potential is limitless.&lt;/p&gt; &lt;h1&gt;Service&lt;/h1&gt; &lt;p&gt;Our passion for cars doesn&amp;rsquo;t end with the sale. We maintain them, we customize them, we recondition them, and we even repair them after an accident. &amp;nbsp;At {{company_name}} we pride ourselves on the best-quality products and the best customer service in the industry.&lt;/p&gt; &lt;h1&gt;Parts&lt;/h1&gt; &lt;p&gt;We believe that our parts team needs to be as reliable as the parts they sell. Our parts experts learn the ins and outs of each model and year to provide an exceptional experience for customers, as well as providing exceptional support to the service team and technicians.&lt;/p&gt; &lt;h1&gt;Finance&lt;/h1&gt; &lt;p&gt;Finance is not just about the numbers. Many of our finance managers come from a sales or service background, understanding the true fundamentals of our business as they have grown within our organization.&amp;nbsp;&lt;/p&gt; &lt;h1&gt;Management&lt;/h1&gt; &lt;p&gt;We strongly believe in growth from within. This means that most of our management team has come through the ranks from every department &amp;ndash; whether that be sales, service, or parts &amp;ndash; and knows the business inside-out. Our culture and beliefs are embedded in this group of leaders.&lt;/p&gt; &lt;h1&gt;Technicians&lt;/h1&gt; &lt;p&gt;In our shops you&amp;#39;ll find the best techs, the best equipment, the best benefits, and the best opportunity to grow in your trade &amp;ndash; all while working on some of the most exciting and exclusive vehicles around.&lt;/p&gt; &lt;p&gt;&amp;nbsp;&lt;/p&gt; &lt;p&gt;&amp;nbsp;&lt;/p&gt; &lt;h1&gt;For Those Who Have Served &amp;nbsp;&lt;/h1&gt; &lt;h1&gt;&lt;a href=&quot;https://www.google.com/imgres?imgurl=https%3A%2F%2Fc2.staticflickr.com%2F8%2F7687%2F17432053881_a14dce603e_b.jpg&amp;amp;imgrefurl=https%3A%2F%2Fwww.flickr.com%2Fphotos%2Falachuacounty%2F17432053881&amp;amp;docid=qpvXZcNHnFAj6M&amp;amp;tbnid=_lZ3Gj2wc_HGDM%3A&amp;amp;w=1024&amp;amp;h=704&amp;amp;bih=1263&amp;amp;biw=1708&amp;amp;ved=0ahUKEwjigYXW77_OAhVX9GMKHQgqAXAQMwg2KAMwAw&amp;amp;iact=mrc&amp;amp;uact=8&quot;&gt;&lt;img alt=&quot;&quot; src=&quot;https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTkcZ_q_8IRz3KrmMMAcpCrnmRAYRchb19P7T9RRebeeUSqvaaL&quot; /&gt;&lt;/a&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp;&lt;/h1&gt; &lt;p&gt;At {{company_name}}, we are appreciative of those who serve our country and are committed to their successful transition back into the civilian workforce.&lt;br /&gt; &lt;br /&gt; We are proud of those who have served in the military and are always excited to actively recruit, hire and train returning veterans.&lt;/p&gt; &lt;/div&gt; &lt;p&gt;&amp;nbsp;&lt;/p&gt; &lt;p&gt;&amp;nbsp;&lt;/p&gt;';
        $theme4_page2                                                           = "INSERT INTO `portal_themes_pages` (`sid`, `company_id`, `theme_name`, `page_unique_name`, `page_name`, `page_title`, `page_content`, `page_banner`, `page_status`, `page_banner_status`, `page_youtube_video`, `page_youtube_video_status`, `page_default_banner`, `video_location`) VALUES (null, " . $user_id . ", 'theme-4', 'page_02', 'career_opportunities', 'Career Opportunities', '" . $page_content ."', 'default_banner_career_opportunities.jpg', '1', '1', '', '1', 'default_banner_career_opportunities.jpg', 'top');";

        //$theme4_page3                                                           = "INSERT INTO `portal_themes_pages` (`sid`, `company_id`, `theme_name`, `page_unique_name`, `page_name`, `page_title`, `page_content`, `page_banner`, `page_status`, `page_banner_status`, `page_youtube_video`, `page_youtube_video_status`, `page_default_banner`, `video_location`) VALUES (null, " . $user_id . ", 'theme-4', 'page_03', 'education_career_advancement', 'Education Career Advancement', '&lt;p&gt;AuomotoHR Automotive Partners focuses on innovation, entrepreneurial ideas, professional business practices and high economic returns makes career development and career management an essential component of our culture. The continuous evolution of our industry and our leadership objectives require new and developed skills, efficient business processes and organization structures aligned with our corporate strategies. To drive the performance of our organization we must have the vision and strategic plan to invest in organizational change and to develop ourselves and our employees as effective resources. To further this commitment, The Education Assistance Program provides eligible full-time employees the opportunity to participate in educational training programs that maintain and enhance skill levels directly related to their job responsibilities or prepare them for possible advancement opportunities within AuomotoHR Automotive Partners. Tuition reimbursement will be made on a course-by-course basis and must be directly related to the employee&rsquo;s career growth.&lt;/p&gt;\r\n&lt;p&gt;While we recognize the significance of developing our current employees, we also understand the value and foresight of looking ahead to the success of our future in supporting and nurturing relationships with academic institutions that provide the automotive industry with future employees. To facilitate this, we have developed relationships with local colleges to provide scholarships and co-operative education opportunities. Full time Georgian Automotive Business students who have completed a coop with one of our dealerships can apply for the H.J. AuomotoHR Scholarship. In addition, a second scholarship is available for the student who attains the highest grade point average.&lt;/p&gt;\r\n', 'banner-3-3HcNJ.jpg', 1, 1, '', 1, 'banner-3-3HcNJ.jpg', 'top')";
        $theme4_page3                                                           = "INSERT INTO `portal_themes_pages` (`sid`, `company_id`, `theme_name`, `page_unique_name`, `page_name`, `page_title`, `page_content`, `page_banner`, `page_status`, `page_banner_status`, `page_youtube_video`, `page_youtube_video_status`, `page_default_banner`, `video_location`) VALUES (null, " . $user_id . ", 'theme-4', 'page_03', 'career_development', 'Career Development', '&lt;p&gt;{{company_name}} focuses on innovation, entrepreneurial ideas, professional business practices and high economic returns makes career development and career management an essential component of our culture. The continuous evolution of our industry and our leadership objectives require new and developed skills, efficient business processes and organization structures aligned with our corporate strategies. To drive the performance of our organization we must have the vision and strategic plan to invest in organizational change and to develop ourselves and our employees as effective resources.&lt;/p&gt;&lt;h2&gt;{{company_name}} Cares&lt;/h2&gt;&lt;p&gt;{{company_name}} believes that our people are the most important part of our team, and we focus on our team members first. Our success in each area of our business is only attainable by having a culture-oriented, well-trained team of professionals who are focused on exceeding customer expectations.&lt;/p&gt;&lt;h2&gt;&amp;nbsp;&lt;/h2&gt;&lt;h2&gt;Variable Operations&lt;/h2&gt;&lt;p&gt;The variable operations at {{company_name}} are committed to providing customers with a professional experience throughout every step of the sales process. With ongoing training provided by&amp;nbsp;dealership managers, our variable team, which including guest services, business development, sales, and financial services, shares a commitment to customer satisfaction. We value our customers and recommit ourselves every day to exceeding their expectations. Typical career opportunities in variable operations include:&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Guest Services Representative&lt;/li&gt;&lt;li&gt;Business Development Representative&lt;/li&gt;&lt;li&gt;Sales Consultant&lt;/li&gt;&lt;li&gt;E-Commerce&lt;/li&gt;&lt;li&gt;Financial Services Manager&lt;/li&gt;&lt;li&gt;Sales Manager&lt;/li&gt;&lt;li&gt;Retail Operations Manager&lt;/li&gt;&lt;/ul&gt;&lt;h2&gt;Fixed Operations&lt;/h2&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;The backbone of the {{company_name}} is the fixed operations team, which includes the service department, parts departments and collision center. Our highly-trained and experienced team members work diligently to provide customers with timely and high-quality service and maintenance for their vehicles. Typical career opportunities include:&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Service Advisor&lt;/li&gt;&lt;li&gt;Estimator&lt;/li&gt;&lt;li&gt;Automotive Technician (Apprentice through Master Certified)&lt;/li&gt;&lt;li&gt;Collision Center Technician&lt;/li&gt;&lt;li&gt;Parts Counter Sales&lt;/li&gt;&lt;li&gt;Warehousing&lt;/li&gt;&lt;/ul&gt;&lt;h2&gt;Dealership Support&lt;/h2&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;The {{company_name}} management company provides support throughout the&amp;nbsp;organization. Team members at the management company have extensive business experience and automotive industry expertise in the following fields:&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Accounting, Internal Audit, and Tax&lt;/li&gt;&lt;li&gt;Administrative Support&lt;/li&gt;&lt;li&gt;Dealership Operations&lt;/li&gt;&lt;li&gt;Financial Analysis&lt;/li&gt;&lt;li&gt;Human Resources&lt;/li&gt;&lt;li&gt;Information Technology&lt;/li&gt;&lt;li&gt;Real Estate and Construction&lt;/li&gt;&lt;li&gt;Risk Management&lt;/li&gt;&lt;/ul&gt;&lt;h2&gt;Dealership Accounting&lt;/h2&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;The dealership accounting team provides a critical link between each dealership department. These dedicated professionals handle the daily processing customer financial transactions, work with state motor vehicle departments, and manage business transactions with various outside vendors while ensuring timely and accurate reporting of the dealership&amp;#39;s financial statement. Typical career opportunities include:&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Controller&lt;/li&gt;&lt;li&gt;Office Manager&lt;/li&gt;&lt;li&gt;Accounting Team Leader&lt;/li&gt;&lt;li&gt;Accounts Payable/Receivable&lt;/li&gt;&lt;li&gt;Title Clerk&lt;/li&gt;&lt;li&gt;Cashier&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;h2&gt;Leadership Positions&lt;/h2&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;At the heart of the {{company_name}} Culture is the emphasis on promoting from within. The vast majority of our executive team, as well as leaders at our dealerships, has been promoted from within our organization. This process encourages continuity within the organization as people that understand and support the {{company_name}} culture are promoted to key positions. Team members can take advantage of training opportunities to prepare themselves for growth within the organization.&lt;/p&gt;', 'default_banner_career_development.jpg', '1', '1', '', '1', 'default_banner_career_development.jpg', 'top');";
        //$theme4_page4                                                           = "INSERT INTO `portal_themes_pages` (`sid`, `company_id`, `theme_name`, `page_unique_name`, `page_name`, `page_title`, `page_content`, `page_banner`, `page_status`, `page_banner_status`, `page_youtube_video`, `page_youtube_video_status`, `page_default_banner`, `video_location`) VALUES (null, " . $user_id . ", 'theme-4', 'page_04', 'why_work_with_us', 'Why Work With Us', '&lt;p&gt;&lt;strong&gt;The $CompanyName Advantage&lt;/strong&gt;&lt;br /&gt;$CompanyName believes that its people are its most important assets. Together, everyone achieves more. We work as a team, best utilizing our individual talents and skills to reach a common goal. $CompanyName teammates help each other to balance their commitments to God, family and to the community they represent. Our teammates are empowered and trained to be leaders within our organization.&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;At $CompanyName we are defined by our integrity. We believe in doing what is right for our fellow teammates, our customers and our corporate partners. The core values of this company are what shape every action we take and every decision that is made.&lt;/p&gt;&lt;p&gt;Our customers are important to us. They&amp;#39;re family. We genuinely desire for our customers to feel valued and cared about whenever inside our doors. Every day, $CompanyName teammates recommit themselves to exceeding the expectations of our customers.&lt;/p&gt;&lt;p&gt;We are committed to continuing the $CompanyName tradition of success and performance. Our teammates are empowered to overcome obstacles and do what it takes to satisfy our customers, lift up our fellow teammates and build profits.&lt;/p&gt;&lt;p&gt;The automotive industry is rapidly progressing. Every day, $CompanyName takes the initiative to find ways to achieve success through simpler, better, faster and leaner processes. We work daily to invest in the improvement of our teammates and develop resources to offer opportunities for personal growth and development. We strive for excellence in all that we do.&lt;/p&gt;&lt;p&gt;&lt;strong&gt;Mission Statement&lt;/strong&gt;&lt;br /&gt;Our mission is to be the premier quality vehicle retailer in the world; providing the best opportunities for our team members, customers, communities and the manufacturers we represent.&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;$CompanyName&lt;/p&gt;&lt;p&gt;$CompanyName believes that our teammates are our most important assets, along with our culture. Our people are the foundation of our brand. Our success as a whole is dependent on preserving our reputation of respect and quality. We are a company that believes in our core values and upholding them through great people, high standards and best practices.&lt;/p&gt;&lt;p&gt;We pride ourselves on our behavior. We treat our customers, corporate partners and each other with respect. We believe in communication and hold each other accountable for the high standards of excellence.&lt;/p&gt;&lt;p&gt;The emotional connections and the relationships formed between Nissan of Omaha and our customers are based on real experiences. We work one customer at a time, knowing that their time and opinions are valuable and worth our undivided attention.&lt;/p&gt;&lt;p&gt;We are a company that strives for success, but understands that we can only achieve it as a team. We are winners, but we know we cannot rest. We strive to move forward and continue to be the best in the markets we represent.&lt;/p&gt;&lt;p&gt;$CompanyName is consistent, yet adaptable. When you enter our dealership you can be assured that you will have a dependable, quality experience every time. But, like everything else, the automotive industry is quickly changing and $CompanyName will change with it to remain the preferred choice of our customers and partners.&lt;/p&gt;&lt;p&gt;$CompanyName is professional, yet accessible. We believe that every experience should be handled with integrity and class. However, $CompanyName prides itself on its grass-roots foundation and its core values. Every customer, every teammate, every partner is a member of our family.&lt;/p&gt;&lt;p&gt;The $CompanyName name is well known and well respected. Together, we have created an image that&amp;#39;s resulted in a positive public perception. The image of a winner. The perception of quality.&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;Benefits&lt;/strong&gt;&lt;br /&gt;Our team members enjoy a positive working environment with opportunities for professional growth through training and advancement from within the organization. Our team members also enjoy a comprehensive benefits program including:&lt;/p&gt;&lt;p&gt;Medical and prescription coverage&lt;br /&gt;Basic life insurance, 401(k) with company match&lt;br /&gt;Employee Assistance Program&lt;br /&gt;Employee discounts on vehicle purchases, parts and service&lt;br /&gt;We also offer a group of supplemental benefit plans including dental coverage, short-term disability, long-term disability, supplemental life insurance&lt;br /&gt;Comprehensive employee recognition programs&lt;br /&gt;Continued training through $CompanyName and the manufacturer&lt;br /&gt;Opportunities for career advancement&lt;/p&gt;', 'banner-2-7rUeR.jpg', '1', '1', '', '1', 'banner-2-7rUeR.jpg', 'top');";
        $theme4_page4                                                           = "INSERT INTO `portal_themes_pages` (`sid`, `company_id`, `theme_name`, `page_unique_name`, `page_name`, `page_title`, `page_content`, `page_banner`, `page_status`, `page_banner_status`, `page_youtube_video`, `page_youtube_video_status`, `page_default_banner`, `video_location`) VALUES (null, " . $user_id . ", 'theme-4', 'page_04', 'why_work_with_us', 'Why Work With Us', '&lt;div id=&quot;lipsum&quot;&gt;&lt;p&gt;&lt;strong&gt;The {{company_name}} Advantage&lt;/strong&gt;&lt;br /&gt;{{company_name}} believes that its people are its most important assets. Together, everyone achieves more. We work as a team, best utilizing our individual talents and skills to reach a common goal. {{company_name}} teammates help each other to balance their commitments to God, family and to the community they represent. Our teammates are empowered and trained to be leaders within our organization.&lt;br /&gt;&lt;br /&gt;At {{company_name}} we are defined by our integrity. We believe in doing what is right for our fellow teammates, our customers and our corporate partners. The core values of this company are what shape every action we take and every decision that is made.&lt;br /&gt;&lt;br /&gt;Our customers are important to us. They&amp;#39;re family. We genuinely desire for our customers to feel valued and cared about whenever inside our doors. Every day, {{company_name}} teammates recommit themselves to exceeding the expectations of our customers.&lt;br /&gt;&lt;br /&gt;We are committed to continuing the {{company_name}} tradition of success and performance. Our teammates are empowered to overcome obstacles and do what it takes to satisfy our customers, lift up our fellow teammates and build profits.&lt;br /&gt;&lt;br /&gt;The automotive industry is rapidly progressing. Every day, {{company_name}} takes the initiative to find ways to achieve success through simpler, better, faster and leaner processes. We work daily to invest in the improvement of our teammates and develop resources to offer opportunities for personal growth and development. We strive for excellence in all that we do.&lt;/p&gt;&lt;p&gt;&lt;br /&gt;&lt;strong&gt;Mission Statement&lt;/strong&gt;&lt;br /&gt;Our mission is to be the premier quality vehicle retailer in the world; providing the best opportunities for our team members, customers, communities and the manufacturers we represent.&lt;br /&gt;&lt;br /&gt;{{company_name}}&lt;strong&gt;?&lt;/strong&gt;&lt;br /&gt;&lt;br /&gt;{{company_name}} believes that our teammates are our most important assets, along with our culture. Our people are the foundation of our brand. Our success as a whole is dependent on preserving our reputation of respect and quality. We are a company that believes in our core values and upholding them through great people, high standards and best practices.&lt;br /&gt;&lt;br /&gt;We pride ourselves on our behavior. We treat our customers, corporate partners and each other with respect. We believe in communication and hold each other accountable for the high standards of excellence.&lt;br /&gt;&lt;br /&gt;The emotional connections and the relationships formed between {{company_name}} and our customers are based on real experiences. We work one customer at a time, knowing that their time and opinions are valuable and worth our undivided attention.&lt;br /&gt;&lt;br /&gt;We are a company that strives for success, but understands that we can only achieve it as a team. We are winners, but we know we cannot rest. We strive to move forward and continue to be the best in the markets we represent.&lt;br /&gt;&lt;br /&gt;{{company_name}} is consistent, yet adaptable. When you enter our dealership you can be assured that you will have a dependable, quality experience every time. But, like everything else, the automotive industry is quickly changing and {{company_name}} will change with it to remain the preferred choice of our customers and partners.&lt;br /&gt;&lt;br /&gt;{{company_name}} is professional, yet accessible. We believe that every experience should be handled with integrity and class. However, {{company_name}} prides itself on its grass-roots foundation and its core values. Every customer, every teammate, every partner is a member of our family.&lt;br /&gt;&lt;br /&gt;The {{company_name}} name is well known and well respected. Together, we have created an image that&amp;#39;s resulted in a positive public perception. The image of a winner. The perception of quality.&lt;br /&gt;&lt;br /&gt;&lt;strong&gt;Benefits&lt;/strong&gt;&lt;br /&gt;Our team members enjoy a positive working environment with opportunities for professional growth through training and advancement from within the organization. Our team members also enjoy a comprehensive benefits program including:&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Medical and prescription coverage&lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;Basic life insurance, 401(k) with company match&lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;Employee Assistance Program&lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;Employee discounts on vehicle purchases, parts and service&lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;We also offer a group of supplemental benefit plans including dental coverage, short-term disability, long-term disability, supplemental life insurance&amp;nbsp;&lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;Comprehensive employee recognition programs&lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;Continued training through {{company_name}} and the manufacturer&lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;Opportunities for career advancement&lt;/li&gt;&lt;/ul&gt;&lt;/div&gt;', 'default_banner_why_work_for_us.jpg', '1', '1', '', '1', 'default_banner_why_work_for_us.jpg', 'top');";
        //$theme4_page5                                                           = "INSERT INTO `portal_themes_pages` (`sid`, `company_id`, `theme_name`, `page_unique_name`, `page_name`, `page_title`, `page_content`, `page_banner`, `page_status`, `page_banner_status`, `page_youtube_video`, `page_youtube_video_status`, `page_default_banner`, `video_location`) VALUES (null, " . $user_id . ", 'theme-4', 'page_05', 'about_us', 'About Us', '&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque leo purus, auctor at placerat in, hendrerit quis ex. Suspendisse porta sollicitudin sapien, eget fringilla ipsum rutrum fringilla. Mauris eu pharetra nisi. Nullam auctor consectetur orci, vel tincidunt libero blandit id. Donec in dui nec massa tempus tempus quis a leo. Phasellus rutrum leo eu nisi tempus posuere. Aenean aliquet quis lorem at imperdiet. Morbi mi felis, elementum in consequat at, pellentesque eget lectus. Duis sagittis ac enim a elementum. Aliquam pulvinar vehicula nunc a luctus. In vel eros ut dolor egestas accumsan nec vel sem. Etiam justo ipsum, dictum eu dignissim at, auctor ut purus. Aliquam in lectus felis. Morbi pulvinar justo tortor, ut egestas neque laoreet vitae. Nam lectus elit, tempor congue est sed, dapibus gravida diam. Nulla eget velit nisi. Suspendisse potenti. Sed convallis tincidunt justo nec sagittis. Vivamus rutrum, nibh ut euismod finibus, felis dolor lobortis sem, id aliquam erat est at ex. Donec eu mauris non ligula blandit consectetur. Nullam pretium vehicula ornare. Nulla a ex vitae dui sollicitudin fringilla eget eget metus. Fusce in commodo nunc, at aliquet dolor. Vivamus vitae erat vitae sem pharetra aliquam. Aenean at fermentum metus. Sed in pretium massa. Praesent maximus et mi sit amet pharetra. Donec aliquet vestibulum tellus, a malesuada augue. Vivamus lacus mi, consectetur eu vestibulum convallis, venenatis vitae diam. Mauris imperdiet velit vestibulum dui elementum viverra. Fusce scelerisque risus eget vestibulum accumsan. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse augue metus, ornare ac velit ac, pretium dignissim mi. Vivamus vitae tortor vehicula, fermentum eros a, posuere leo. Cras suscipit faucibus enim, non euismod leo maximus in. Proin bibendum nec elit eget bibendum. Aliquam non pellentesque quam. Donec consectetur arcu non metus maximus aliquet. Phasellus sodales commodo porttitor. Curabitur nec rhoncus eros. Vestibulum et orci nec dolor convallis mattis. Pellentesque dictum, felis et iaculis dictum, velit leo gravida erat, ac rutrum lacus metus in odio. Quisque pharetra sagittis malesuada. Cras feugiat sit amet dui vel volutpat. Curabitur nec fermentum nunc, accumsan cursus ante. Phasellus eros nibh, ullamcorper laoreet porttitor nec, elementum ut orci. Donec facilisis ultricies est. Phasellus convallis, nunc sed faucibus posuere, dolor metus viverra velit, sit amet sollicitudin sem ante non ex. Donec efficitur massa vel metus pulvinar pharetra. Proin nunc tellus, porta vel diam non, luctus ullamcorper tellus. Nunc tincidunt et sem ut fermentum. Aenean metus ante, pulvinar in nisl eleifend, lobortis feugiat nibh. Duis nec suscipit erat. Proin eget facilisis magna. Cras vel ligula vel odio fringilla auctor eu venenatis magna. Nulla tincidunt ultrices nisi, quis eleifend urna fermentum ac. Praesent vitae turpis ac nunc placerat dapibus sit amet eu lectus. Integer viverra nisi eros, id sollicitudin ipsum blandit id. Aenean eget lectus vehicula, convallis ligula vitae, luctus mi. Proin tristique fermentum nunc quis pellentesque. Nam vulputate augue rhoncus lorem consequat elementum. Donec facilisis ligula nec pharetra tincidunt. Cras suscipit venenatis orci et commodo. Aliquam sagittis dui ut dictum consequat. Aenean finibus, felis in maximus vestibulum, turpis quam tempus sapien, vitae mollis diam lectus ac ipsum.&lt;/p&gt;\r\n', 'banner-2-7rUeR.jpg', 1, 0, '', 1, 'banner-2-7rUeR.jpg', 'top')";
        $theme4_page5                                                           = "INSERT INTO `portal_themes_pages` (`sid`, `company_id`, `theme_name`, `page_unique_name`, `page_name`, `page_title`, `page_content`, `page_banner`, `page_status`, `page_banner_status`, `page_youtube_video`, `page_youtube_video_status`, `page_default_banner`, `video_location`) VALUES (null, " . $user_id . ", 'theme-4', 'page_05', 'about_us', 'About Us', '&lt;p&gt;The {{company_name}} is chipping in to help Imagine a Better St. Louis. We want to work to enrich communities, give our great city a new sense of hope and help end violence throughout the St. Louis area. We invite you to join us and the collective efforts of many St. Louis entities and Imagine a Better St. Louis.&lt;/p&gt;&lt;p&gt;After thousands in the St. Louis area have given us their business over the years, Jim Butler Auto Group wants to give back to the St. Louis community. Our efforts to become deeply involved in the community throughout the years keep growing. We work with many cities, sponsorships and local organizations such as the Boys and Girls Club of Greater St. Louis, the American Cancer Society and our STL Safe Driver program.&lt;/p&gt;&lt;p&gt;We are also active in the Fenton, Kirkwood, Des Peres and Crestwood chambers of commerce in order to help drive the economy of those areas. As we want to continue to give back to St. Louis, we do have there are quarterly meetings to review any proposed sponsorships, charity events and the proposals that go along with them.&amp;nbsp;&lt;/p&gt;&lt;p&gt;At the {{company_name}}, we believe in being an active part of our community. We look forward to working with you to help grow and strengthen our community&lt;/p&gt;', 'banner-2-7rUeR.jpg', '0', '0', '', '1', 'banner-2-7rUeR.jpg', 'top');";
        //Theme 4 Testimonials
        $theme4_testimonial1                                                    = "INSERT INTO `portal_testimonials` (`author_name`, `designation`, `short_description`, `type`, `status`, `company_id`, `resource_name`, `full_description`, `youtube_video_id`) VALUES ('User One', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ac diam id urna vehicula volutpat in nec nibh. Nullam eget lacus felis. Vivamus turpis nunc, suscipit ut augue et, sollicitudin eleifend massa. Cras vel nunc at odio molestie feugiat. ', 'image', 1, " . $user_id . ", 'worker37-jby2I.png', '&lt;p&gt;&lt;span style=&quot;color:rgb(0, 0, 0); font-family:arial,helvetica,sans; font-size:11px&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ac diam id urna vehicula volutpat in nec nibh. Nullam eget lacus felis. Vivamus turpis nunc, suscipit ut augue et, sollicitudin eleifend massa. Cras vel nunc at odio molestie feugiat. Etiam vitae elit nisi. Nam ullamcorper arcu ac sem viverra, a faucibus odio ultricies. Nulla pretium efficitur diam vel ullamcorper. Morbi ut ipsum sit amet leo lacinia vehicula. Maecenas id sapien volutpat nibh tincidunt tincidunt id sit amet orci. Etiam hendrerit a nibh vel malesuada. Phasellus eget pretium elit, non pellentesque elit.&lt;/span&gt;&lt;/p&gt;\r\n', '')";
        $theme4_testimonial2                                                    = "INSERT INTO `portal_testimonials` (`author_name`, `designation`, `short_description`, `type`, `status`, `company_id`, `resource_name`, `full_description`, `youtube_video_id`) VALUES ('User Two', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ac diam id urna vehicula volutpat in nec nibh. Nullam eget lacus felis. Vivamus turpis nunc, suscipit ut augue et, sollicitudin eleifend massa. Cras vel nunc at odio molestie feugiat. Etiam vitae elit nisi.', 'image', 1, " . $user_id . ", 'user1-vk6oC.jpg', '&lt;p&gt;&lt;span style=&quot;color:rgb(0, 0, 0); font-family:arial,helvetica,sans; font-size:11px&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ac diam id urna vehicula volutpat in nec nibh. Nullam eget lacus felis. Vivamus turpis nunc, suscipit ut augue et, sollicitudin eleifend massa. Cras vel nunc at odio molestie feugiat. Etiam vitae elit nisi. Nam ullamcorper arcu ac sem viverra, a faucibus odio ultricies. Nulla pretium efficitur diam vel ullamcorper. Morbi ut ipsum sit amet leo lacinia vehicula. Maecenas id sapien volutpat nibh tincidunt tincidunt id sit amet orci. Etiam hendrerit a nibh vel malesuada. Phasellus eget pretium elit, non pellentesque elit.&lt;/span&gt;&lt;/p&gt;\r\n', '')";
        $theme4_testimonial3                                                    = "INSERT INTO `portal_testimonials` (`author_name`, `designation`, `short_description`, `type`, `status`, `company_id`, `resource_name`, `full_description`, `youtube_video_id`) VALUES ('User Three', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ac diam id urna vehicula volutpat in nec nibh. Nullam eget lacus felis. Vivamus turpis nunc, suscipit ut augue et, sollicitudin eleifend massa. Cras vel nunc at odio molestie feugiat. Etiam vitae elit nisi.', 'image', 1, " . $user_id . ", 'user4-dF49I.png', '&lt;p&gt;&lt;span style=&quot;color:rgb(0, 0, 0); font-family:arial,helvetica,sans; font-size:11px&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ac diam id urna vehicula volutpat in nec nibh. Nullam eget lacus felis. Vivamus turpis nunc, suscipit ut augue et, sollicitudin eleifend massa. Cras vel nunc at odio molestie feugiat. Etiam vitae elit nisi. Nam ullamcorper arcu ac sem viverra, a faucibus odio ultricies. Nulla pretium efficitur diam vel ullamcorper. Morbi ut ipsum sit amet leo lacinia vehicula. Maecenas id sapien volutpat nibh tincidunt tincidunt id sit amet orci. Etiam hendrerit a nibh vel malesuada. Phasellus eget pretium elit, non pellentesque elit.&lt;/span&gt;&lt;/p&gt;\r\n', '')";
        $theme4_testimonial4                                                    = "INSERT INTO `portal_testimonials` (`author_name`, `designation`, `short_description`, `type`, `status`, `company_id`, `resource_name`, `full_description`, `youtube_video_id`) VALUES ('User Four', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ac diam id urna vehicula volutpat in nec nibh. Nullam eget lacus felis. Vivamus turpis nunc, suscipit ut augue et, sollicitudin eleifend massa. Cras vel nunc at odio molestie feugiat. Etiam vitae elit nisi.', 'image', 1, " . $user_id . ", 'user3-2Kwe8.jpg', '&lt;p&gt;&lt;span style=&quot;color:rgb(0, 0, 0); font-family:arial,helvetica,sans; font-size:11px&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ac diam id urna vehicula volutpat in nec nibh. Nullam eget lacus felis. Vivamus turpis nunc, suscipit ut augue et, sollicitudin eleifend massa. Cras vel nunc at odio molestie feugiat. Etiam vitae elit nisi. Nam ullamcorper arcu ac sem viverra, a faucibus odio ultricies. Nulla pretium efficitur diam vel ullamcorper. Morbi ut ipsum sit amet leo lacinia vehicula. Maecenas id sapien volutpat nibh tincidunt tincidunt id sit amet orci. Etiam hendrerit a nibh vel malesuada. Phasellus eget pretium elit, non pellentesque elit.&lt;/span&gt;&lt;/p&gt;\r\n', '')";
        //Theme 4 Meta Information
        $theme4_meta1                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (null, 'home', " . $user_id . ", 'partners', 'a:2:{i:0;a:3:{s:16:\"txt_partner_name\";s:16:\"Chevrolet Motors\";s:15:\"txt_partner_url\";s:21:\"http://www.google.com\";s:17:\"file_partner_logo\";s:24:\"partner-logo-6-qCOKH.jpg\";}i:1;a:3:{s:16:\"txt_partner_name\";s:13:\"Mercedes Benz\";s:15:\"txt_partner_url\";s:22:\"https://www.google.com\";s:17:\"file_partner_logo\";s:30:\"partner-logo-4-Navdx-G7zv4.jpg\";}}', 'theme-4')";
        $theme4_meta2                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (null, 'jobs', " . $user_id . ", 'jobs_page_banner', 'a:1:{s:16:\"jobs_page_banner\";s:18:\"banner-1-kvinR.jpg\";}', 'theme-4')";

        //$theme4_meta3                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (NULL, 'home', " . $user_id . ", 'footer_content', 'a:2:{s:5:\"title\";s:23:\"JOIN A WINNING TEAM AT \";s:7:\"content\";s:642:\"At \"Store Name\" we thrive on inspiring pride among our team members and envy among our competitors. Our culture rests on providing opportunity and challenge for our team members while encouraging healthy work-life balance. To keep growing and building on our success we look for like-minded individuals to join our team. Bring your passion, energy and experience. We will give you the training, support and encouragement to succeed. To keep growing and building on our success we look for like-minded individuals to join our team. Bring your passion, energy and experience. We will give you the training, support and encouragement to succeed.\";}', 'theme-4');";        
        //$theme4_meta3                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (NULL, 'home', " . $user_id . ", 'footer_content', 'a:2:{s:5:\"title\";s:39:\"JOIN A WINNING TEAM AT {{company_name}}\";s:7:\"content\";s:646:\"At {{company_name}} we thrive on inspiring pride among our team members and envy among our competitors. Our culture rests on providing opportunity and challenge for our team members while encouraging healthy work-life balance. To keep growing and building on our success we look for like-minded individuals to join our team. Bring your passion, energy and experience. We will give you the training, support and encouragement to succeed. To keep growing and building on our success we look for like-minded individuals to join our team. Bring your passion, energy and experience. We will give you the training, support and encouragement to succeed.\";}', 'theme-4');";
        $theme4_meta3                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (NULL, 'home', " . $user_id . ", 'footer_content', 'a:2:{s:5:\"title\";s:39:\"JOIN A WINNING TEAM AT {{company_name}}\";s:7:\"content\";s:467:\"<p>At {{company_name}} we thrive on inspiring pride among our team members and envy among our competitors.</p><p>Our culture rests on providing opportunity and challenge for our team members while encouraging healthy work-life balance.</p><p>To keep growing and building on our success we look for like-minded individuals to join our team.</p><p>Bring your passion, energy and experience.</p><p>We will give you the training, support and encouragement to succeed.</p>\";}', 'theme-4');";
        //$theme4_meta4                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (null, 'home', " . $user_id . ", 'section_01', 'a:7:{s:5:\"title\";s:18:\"Looking for a Job?\";s:8:\"tag_line\";s:34:\"There is No Better Place To Start!\";s:19:\"show_video_or_image\";s:5:\"video\";s:5:\"image\";s:11:\"1-e1qhe.jpg\";s:5:\"video\";s:11:\"STRPsW6IY8k\";s:6:\"status\";s:1:\"1\";s:7:\"content\";s:23:\"This is a test message.\";}', 'theme-4')";
        $theme4_meta4                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (null, 'home', " . $user_id . ", 'section_01', 'a:7:{s:5:\"title\";s:50:\"Are You Looking For an Amazing Career Opportunity?\";s:8:\"tag_line\";s:25:\"There is No Better Place!\";s:19:\"show_video_or_image\";s:5:\"video\";s:5:\"image\";s:11:\"1-e1qhe.jpg\";s:5:\"video\";s:11:\"STRPsW6IY8k\";s:6:\"status\";s:1:\"1\";s:7:\"content\";s:23:\"This is a test message.\";}', 'theme-4');";        
        //$theme4_meta5                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (null, 'home', " . $user_id . ", 'section_02', 'a:7:{s:5:\"image\";s:13:\"tab-6GRW9.jpg\";s:5:\"video\";s:0:\"\";s:5:\"title\";s:11:\"OUR CULTURE\";s:8:\"tag_line\";s:0:\"\";s:7:\"content\";s:751:\"We boast a diverse, energetic, dedicated and fast-paced culture in which teamwork and ingenuity thrive. We strive to create an environment that is entrepreneurial, nurtures personal development and builds on unique talents. Team member passion and commitment to excellence have helped make &quot;Store Name&quot; a leader in the industry. It&rsquo;s why &quot;Store Name&quot; stands out as a category leader and why people want to work with us, organizations want to partner with us and why clients come to us time and again as a leading resource for for all of their vehicle needs. Above all else, it makes &quot;Store Name&quot; a special company, one that can attribute its success to its products, clients, and most importantly, its team members.\";s:6:\"status\";s:1:\"1\";s:19:\"show_video_or_image\";s:5:\"image\";}', 'theme-4');";
        //$theme4_meta5                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (null, 'home', " . $user_id . ", 'section_02', 'a:7:{s:5:\"image\";s:13:\"tab-6GRW9.jpg\";s:5:\"video\";s:0:\"\";s:5:\"title\";s:11:\"OUR CULTURE\";s:8:\"tag_line\";s:0:\"\";s:7:\"content\";s:733:\"We boast a diverse, energetic, dedicated and fast-paced culture in which teamwork and ingenuity thrive. We strive to create an environment that is entrepreneurial, nurtures personal development and builds on unique talents. Team member passion and commitment to excellence have helped make {{company_name}} a leader in the industry. It&rsquo;s why {{company_name}} stands out as a category leader and why people want to work with us, organizations want to partner with us and why clients come to us time and again as a leading resource for for all of their vehicle needs. Above all else, it makes {{company_name}} a special company, one that can attribute its success to its products, clients, and most importantly, its team members.\";s:6:\"status\";s:1:\"1\";s:19:\"show_video_or_image\";s:5:\"image\";}', 'theme-4');";

        $serialized_info                                                        = 'a:8:{s:5:"image";s:13:"tab-6GRW9.jpg";s:5:"video";s:0:"";s:5:"title";s:11:"OUR CULTURE";s:8:"tag_line";s:0:"";s:7:"content";s:951:"<p><span style="font-size:20px">We boast a diverse, energetic, dedicated and fast-paced culture in which teamwork and ingenuity thrive.</span></p>  <p><span style="font-size:20px">We strive to create an environment that is entrepreneurial, nurtures personal development and builds on unique talents.</span></p> <p><span style="font-size:20px">Team member passion and commitment to excellence have helped make {{company_name}} a leader in the industry.</span></p>  <p><span style="font-size:20px">It&rsquo;s why {{company_name}} stands out as a category leader and why people want to work with us, organizations want to partner with us and why clients come to us time and again as a leading resource for for all of their vehicle needs.</span></p>  <p><span style="font-size:20px">Above all else, it makes {{company_name}} a special company, one that can attribute its success to its products, clients, and most importantly, its team members.</span></p>";s:6:"status";s:1:"1";s:19:"show_video_or_image";s:5:"image";s:11:"column_type";s:10:"right_left";}';
        $theme4_meta5                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (null, 'home', " . $user_id . ", 'section_02', '" . addslashes($serialized_info) .  "', 'theme-4');";
        //$theme4_meta6                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (null, 'home', " . $user_id . ", 'section_03', 'a:7:{s:5:\"image\";s:14:\"tab1-0Atpg.jpg\";s:5:\"video\";s:0:\"\";s:5:\"title\";s:12:\"OUR BENEFITS\";s:8:\"tag_line\";s:0:\"\";s:7:\"content\";s:913:\"We know that a successful workplace is an engaged workplace. In order for &quot;Store Name&quot; to be the best, we need to attract, retain and reward the best. We do this by offering competitive compensation and benefits packages for our team members. Health Prescription drug plan Long-term disability Life/accidental death and personal loss coverage (AD&amp;PL) Flexible spending accounts (healthcare and dependent care) Wealth Competitive base salaries Annual bonus potential Incentive and commission programs (for eligible departments) 401(k) plan with company matching Pension plan and retiree healthcare Living Paid time off (PTO) Adoption assistance Team member assistance program Team member discount programs with leading retailers Community outreach opportunities Career Training, development and mentorship programs Individualized training programs Leadership development Team member discount programs\";s:6:\"status\";s:1:\"1\";s:19:\"show_video_or_image\";s:5:\"image\";}', 'theme-4');";
        //$theme4_meta6                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (null, 'home', " . $user_id . ", 'section_03', 'a:7:{s:5:\"image\";s:14:\"tab1-0Atpg.jpg\";s:5:\"video\";s:0:\"\";s:5:\"title\";s:12:\"OUR BENEFITS\";s:8:\"tag_line\";s:0:\"\";s:7:\"content\";s:908:\"We know that a successful workplace is an engaged workplace. In order for {{company_name}} to be the best, we need to attract, retain and reward the best. We do this by offering competitive compensation and benefits packages for our team members. Health Prescription drug plan Long-term disability Life/accidental death and personal loss coverage (AD&amp;PL) Flexible spending accounts (healthcare and dependent care) Wealth Competitive base salaries Annual bonus potential Incentive and commission programs (for eligible departments) 401(k) plan with company matching Pension plan and retiree healthcare Living Paid time off (PTO) Adoption assistance Team member assistance program Team member discount programs with leading retailers Community outreach opportunities Career Training, development and mentorship programs Individualized training programs Leadership development Team member discount programs.\";s:6:\"status\";s:1:\"1\";s:19:\"show_video_or_image\";s:5:\"image\";}', 'theme-4');";
        //$theme4_meta6                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (null, 'home', " . $user_id . ", 'section_03', 'a:7:{s:5:\"image\";s:14:\"tab1-0Atpg.jpg\";s:5:\"video\";s:0:\"\";s:5:\"title\";s:12:\"OUR BENEFITS\";s:8:\"tag_line\";s:0:\"\";s:7:\"content\";s:369:\"<p>We know that a successful workplace is an engaged workplace.</p><p>In order for {{company_name}} to be the best, we need to attract, retain and reward the best.</p><p>We do this by offering competitive compensation and benefits packages for our team members.</p><p>Health benefits, Incentive and commission programs (for eligible departments) 401(k) savings plan</p>\";s:6:\"status\";s:1:\"1\";s:19:\"show_video_or_image\";s:5:\"image\";}', 'theme-4');";

        $serialized_info                                                        = 'a:8:{s:5:"image";s:14:"tab1-0Atpg.jpg";s:5:"video";s:0:"";s:5:"title";s:12:"OUR BENEFITS";s:8:"tag_line";s:0:"";s:7:"content";s:514:"<p><span style="font-size:20px">We know that a successful workplace is an engaged workplace.</span></p><p><span style="font-size:20px">In order for {{company_name}} to be the best, we need to attract, retain and reward the best.</span></p><p><span style="font-size:20px">We do this by offering competitive compensation and benefits packages for our team members.</span></p><p><span style="font-size:20px">Health benefits, Incentive and commission programs (for eligible departments) 401(k) savings plan.</span></p>";s:6:"status";s:1:"1";s:19:"show_video_or_image";s:5:"image";s:11:"column_type";s:10:"left_right";}';
        $theme4_meta6                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (null, 'home', " . $user_id . ", 'section_03', '"  . addslashes($serialized_info) . "', 'theme-4');";

        $theme4_meta7                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (null, 'home', " . $user_id . ", 'section_04', 'a:7:{s:5:\"image\";s:0:\"\";s:5:\"video\";s:11:\"STRPsW6IY8k\";s:5:\"title\";s:0:\"\";s:8:\"tag_line\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"status\";s:1:\"0\";s:19:\"show_video_or_image\";s:4:\"none\";}', 'theme-4')";
        $theme4_meta8                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (null, 'home', " . $user_id . ", 'section_05', 'a:7:{s:5:\"title\";s:40:\"OUR PARTNER COMPANY CAREER OPPORTUNITIES\";s:8:\"tag_line\";s:25:\"There is No Better Place!\";s:19:\"show_video_or_image\";s:5:\"image\";s:5:\"image\";s:11:\"1-e1qhe.jpg\";s:5:\"video\";s:11:\"STRPsW6IY8k\";s:6:\"status\";s:1:\"0\";s:7:\"content\";s:23:\"This is a test message.\";}', 'theme-4');";
        $theme4_meta9                                                           = "INSERT INTO `portal_themes_meta_data` (`sid`, `page_name`, `company_id`, `meta_key`, `meta_value`, `theme_name`) VALUES (null, 'home', " . $user_id . ", 'section_06', 'a:7:{s:5:\"image\";s:0:\"\";s:5:\"video\";s:0:\"\";s:5:\"title\";s:12:\"Testimonials\";s:8:\"tag_line\";s:29:\"KIND WORDS FROM HAPPY MEMBERS\";s:7:\"content\";s:0:\"\";s:6:\"status\";s:1:\"0\";s:19:\"show_video_or_image\";s:5:\"image\";}', 'theme-4')";
        //Facebook configuration Query
        $facebook_cofig                                                         = "INSERT INTO `facebook_configuration` (`company_sid`) VALUES ($user_id)";
        $this->db->query($theme1);
        $this->db->query($theme2);
        $this->db->query($theme3);
        //Theme 4 Queries Execution Start
        $this->db->query($theme4);
        $this->db->query($theme4_page1);
        $this->db->query($theme4_page2);
        $this->db->query($theme4_page3);
        $this->db->query($theme4_page4);
        $this->db->query($theme4_page5);
        $this->db->query($theme4_testimonial1);
        $this->db->query($theme4_testimonial2);
        $this->db->query($theme4_testimonial3);
        $this->db->query($theme4_testimonial4);
        $this->db->query($theme4_meta1);
        $this->db->query($theme4_meta2);
        $this->db->query($theme4_meta3);
        $this->db->query($theme4_meta4);
        $this->db->query($theme4_meta5);
        $this->db->query($theme4_meta6);
        $this->db->query($theme4_meta7);
        $this->db->query($theme4_meta8);
        $this->db->query($theme4_meta9);      
        $this->db->query($facebook_cofig);
        //Theme 4 Queries Execution End
        $data["emp_id"]                                                         = $emp_id;
        $data["company_id"]                                                     = $user_id;
        $company_data['sid'] = $user_id;
        $company_data['sub_domain'] = $employer_portal_data['sub_domain'];
        $company_details['company_details'] = $company_data;
        send_settings_to_remarket(REMARKET_PORTAL_SYNC_COMPANY_URL,$company_details);
        return $data;
    }

    function login($username, $password) {
        //$this->db->select('sid, Logo, username, career_page_type, email, ip_address, registration_date, expiry_date, active, activation_key, verification_key, parent_sid, Location_Country, Location_State, Location_City, Location_Address, PhoneNumber, CompanyName, ContactName, WebSite, Location_ZipCode, profile_picture, first_name, last_name, access_level, job_title, full_employment_application, background_check, job_listing_template_group, linkedin_profile_url, discount_type, discount_amount, has_job_approval_rights, has_applicant_approval_rights, is_primary_admin, is_executive_admin, marketing_agency_sid, is_paid, job_category_industries_sid');
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->where('password', MD5($password));
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->limit(1);
        $emp_query                                                              = $this->db->get();

        if ($emp_query->num_rows() == 1) {
                $employer                                                       = $emp_query->result_array();
                //$this->db->select('sid, Logo, username, career_page_type, email, ip_address, registration_date, expiry_date, active, activation_key, verification_key, parent_sid, Location_Country, Location_State, Location_City, Location_Address, PhoneNumber, CompanyName, ContactName, WebSite, Location_ZipCode, profile_picture, first_name, last_name, access_level, job_title, full_employment_application, background_check, job_listing_template_group, linkedin_profile_url, discount_type, discount_amount, has_job_approval_rights, has_applicant_approval_rights, is_primary_admin, is_executive_admin, marketing_agency_sid, is_paid, job_category_industries_sid');
                $this->db->select('*');
                $this->db->from('users');
                $this->db->where('sid', $employer[0]['parent_sid']);
                $this->db->limit(1);
                $company                                                        = $this->db->get()->result_array();

                $this->db->select('*');
                $this->db->from('portal_employer');
                $this->db->where('user_sid', $employer[0]['parent_sid']);
                $this->db->limit(1);
                $portal                                                         = $this->db->get()->result_array();

                /* temporary block // fetch clocked in Status
                $company_sid = $employer[0]['parent_sid'];
                $employer_sid = $employer[0]['sid'];
                $this->db->select('sid, attendance_type, attendance_date');
                $this->db->where('company_sid', $company_sid);
                $this->db->where('employer_sid', $employer_sid);
                $this->db->order_by('attendance_date', 'DESC');
                $this->db->limit(1);
                $attendance = $this->db->get('attendance')->result_array();
                
                if ( !empty( $attendance ) ) {
                    $attendance                                                 = $attendance[0];
                }
                */
                $attendance                                                     = array();
                $cart                                                           = db_get_cart_content($employer[0]['parent_sid']);
                $data['employer']                                               = $employer[0];
                $data['company']                                                = $company[0];
                $data['cart']                                                   = $cart;
                $data['portal']                                                 = $portal[0];
                $data['clocked_status']                                         = $attendance;

                $this->db->where('company_id',$company[0]['sid']);
                $config = $this->db->get('incident_type_configuration')->num_rows();
                $this->session->set_userdata('incident_config',$config);

                $this->session->set_userdata('resource_center',$company[0]['enable_resource_center']);

            return $data;
        } else {
            return false;
        }
    }

    function check_user($username) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->limit(1);
        $emp_query                                                              = $this->db->get();
        
        if ($emp_query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    function check_email($email) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email);
        $this->db->limit(1);
        $emp_query                                                              = $this->db->get();
        
        if ($emp_query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    function save_email_logs($data) {
        $this->db->insert('email_log', $data);
    }

    function varification_key($user_email, $random_string) {
        $this->db->where('email', $user_email);
        $data                                                                   = array('verification_key' => $random_string);
        $this->db->update('users', $data);
    }

    function varification_user_key($user_name, $random_key) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $user_name);
        $this->db->where('verification_key', $random_key);
        $data                                                                   = $this->db->get();

        if ($data->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function change_password($password, $user, $key) {
        $data                                                                   = array('password' => $password);
        $this->db->where('username', $user);
        $this->db->where('verification_key', $key);
        $this->db->update('users', $data);
    }

    function reset_key($user) {
        $data                                                                   = array('verification_key' => NULL);
        $this->db->where('username', $user);
        $this->db->update('users', $data);
    }

    function email_user_data($email) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email);
        $this->db->limit(1);
        $this->db->order_by('sid', 'DESC');
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $query_result  = $this->db->get();
        
        if ($query_result->num_rows() > 0) {
            return $query_result->row_array();
        }
        return false;
    }

    function username_user_data($user) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $user);
        $this->db->limit(1);
        $query_result                                                           = $this->db->get();
        
        if ($query_result->num_rows() > 0) {
            return $row = $query_result->row_array();
        }
    }

    function user_data_by_id($sid) {
        $this->db->from('users');
        $this->db->where('sid', $sid);
        $this->db->limit(1);
        $query_result                                                           = $this->db->get();
        
        if ($query_result->num_rows() > 0) {
            $row = $query_result->row_array();
            return $row;
        }
    }
    
    function check_clocked_in_activity($company_sid, $employer_sid) {
        $this->db->select('sid, attendance_type, attendance_date');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->order_by('attendance_date', 'DESC');
        $this->db->limit(1);

        $attendance = $this->db->get('attendance')->result_array();

        if (!empty($attendance)) {
            return $attendance[0];
        } else {
            return array();
        }
    }

    function check_key($key){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('salt', $key);
        $this->db->limit(1);
        $query_result = $this->db->get()->result_array();
        if(sizeof($query_result)>0){
            return 1;
        }
        else{
            return 0;
        }
    }

    function updatePass($password,$key) {
        $data = array('password' => MD5($password),'salt' => NULL);
        $this->db->where('salt', $key);
        $this->db->update('users', $data);
//        $update_id = $this->db->affected_rows();
//        if($update_id){
//            $this->db->select('*');
//            $this->db->from('executive_users');
//            $this->db->where('id', $update_id);
//            $this->db->limit(1);
//            $executive_query = $this->db->get();
//            //echo $this->db->last_query();
//            if ($executive_query->num_rows() == 1) {
//                $executive_users = $executive_query->result_array();
//                $status = $executive_users[0]['active']; // check the status whether the account is active or inactive
//
//                if ($status == 1) {
//
//                    $data['status'] = 'active';
//                    $data['executive_user'] = $executive_users[0];
//                } else {
//                    $data['status'] = 'inactive';
//                }
//            }
//        }
//        else
//            return false;
    }
    
    function check_if_blocked($applicant_email) {
        $this->db->where('applicant_email', $applicant_email);
        $this->db->from('blocked_applicants');
        $count = $this->db->count_all_results();

        if ($count > 0) {
            return 'blocked';
        } else {
            return 'not-blocked';
        }
    }

    function checkTerminated($company_sid){
        $this->db->select('sid');
        $this->db->where('parent_sid',$company_sid);
        $this->db->where('terminated_status',1);
        $this->db->where('active',1);
        $terminatedEmp = $this->db->get('users')->result_array();
        if(sizeof($terminatedEmp) > 0){
            $active = array('active' => 0);
            foreach($terminatedEmp as $id){
                $this->db->select('termination_date');
                $this->db->where('employee_sid',$id['sid']);
                $this->db->where('employee_status',1);
                $this->db->order_by('created_at','DESC');
                $result = $this->db->get('terminated_employees')->result_array();
                if(sizeof($result)){
                    if($result[0]['termination_date'] <= date('Y-m-d')){
                        $this->db->where('sid',$id['sid']);
                        $this->db->update('users',$active);
                    }
                }
            }

        }

    }


    /**
     * Check the onboarding address
     */
    public function checkCompanyOnboardAddress(
        int $companyId
    ) {
        //
        return
            $this->db
            ->where([
                'company_sid' => $companyId
            ])
            ->count_all_results('onboarding_office_locations');
    }

    /**
     * Get company primary address
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyAddress(
        int $companyId
    ) {
        //
        return
            $this->db
            ->select([
                'users.Location_Address',
                'users.Location_City',
                'users.Location_ZipCode',
                'users.PhoneNumber',
                'states.state_name',
                'countries.country_name'
            ])
            ->join('states', 'states.sid = users.Location_State', 'left')
            ->join('countries', 'countries.sid = users.Location_Country', 'left')
            ->where([
                'users.sid' => $companyId
            ])
            ->get('users')
            ->row_array();
    }

    /**
     * Get company address location
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyAddressLocation(
        int $companyId
    ) {
        //
        return
            $this->db
            ->select([
                'address'
            ])
            ->where([
                'company_sid' => $companyId
            ])
            ->get('company_addresses_locations')
            ->row_array();
    }

    /**
     * Copy the company default address as onboarding address
     *
     * @param int $companyId
     * @param int $fromScript
     * @return int
     */
    public function fixOnboardingAddress(
        int $companyId,
        int $fomScript = 1
    ) {
        // Check the onboarding address
        $onboardAddress = $this->checkCompanyOnboardAddress($companyId);
        //
        if ($onboardAddress != 0) {
            return 0;
        }
        // Get company default address
        $companyAddress = $this->getCompanyAddress($companyId);
        //
        if (!empty($companyAddress)) {
            //
            $address = '';
            $address .= !empty($companyAddress['Location_Address']) ? $companyAddress['Location_Address'] . ', ' : '';
            $address .= !empty($companyAddress['Location_City']) ? $companyAddress['Location_City'] . ', ' : '';
            $address .= !empty($companyAddress['Location_ZipCode']) ? $companyAddress['Location_ZipCode'] . ', ' : '';
            $address .= !empty($companyAddress['state_name']) ? $companyAddress['state_name'] . ', ' : '';
            $address .= !empty($companyAddress['country_name']) ? $companyAddress['country_name'] : '';
            //
            $address = trim(ltrim(rtrim($address, ', '), ', '));
        } else {
            //
            $companyAddress = $this->getCompanyAddressLocation($companyId);
            //
            if (empty($companyAddress)) {
                return 0;
            }
            //
            $address = $companyAddress['address'];
            $companyAddress['PhoneNumber'] = '';
        }
        //
        $ins = [];
        $ins['company_sid'] = $companyId;
        $ins['location_title'] = 'Primary Location';
        $ins['location_address'] = $address;
        $ins['location_telephone'] = $companyAddress['PhoneNumber'];
        $ins['location_fax'] = '';
        $ins['location_status'] = 1;
        $ins['date_created'] = date('Y-m-d H:i:s', strtotime('now'));
        $ins['is_primary'] = 1;
        $ins['by_script'] = $fomScript;
        //
        $this->db->insert('onboarding_office_locations', $ins);
        //
        return $this->db->insert_id();
    }
}

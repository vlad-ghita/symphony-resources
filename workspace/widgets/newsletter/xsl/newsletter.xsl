<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
		xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
		xmlns:exsl="http://exslt.org/common"
		xmlns:fl="http://symphony-cms.com/functions"
		xmlns:string="http://symphony-cms.com/functions"
		xmlns:url="http://ns.nbsp.io/xsl/url"
		xmlns:utils="http://exslt.org/utils"
		xmlns:xcms="http://xanderadvertising.com/functions"
		extension-element-prefixes="exsl fl string url utils xcms">




	<!-- This widget adds CSS -->

	<xsl:template match="data" mode="add_head_css">
		<xsl:apply-imports/>

		<!-- Widget specific CSS -->
		<link rel="stylesheet" type="text/css" media="screen" href="{/data/params/workspace}/{/data/paths/widgets}/newsletter/{/data/paths/widgets_css}/newsletter.css"/>
	</xsl:template>




	<!-- This widget adds JS -->

	<xsl:template match="data" mode="add_body_js">
		<xsl:apply-imports/>

		<!-- Validation plugin -->
		<script type="text/javascript" src="{/data/params/workspace}/{/data/paths/js}/jquery.validate.min.js"/>

		<!-- Widget specific JS -->
		<script type="text/javascript" src="{/data/params/workspace}/{/data/paths/widgets}/newsletter/{/data/paths/widgets_js}/newsletter.js"/>
	</xsl:template>




	<!-- Main -->

	<xsl:template name="w_newsletter">
		<xsl:variable name="event" select="/data/events/mailchimp"/>

		<hr/>

		<div id="newsletter" class="newsletter">
			<h3>Newsletter</h3>

			<p>Stay up to date with our latest offers and news:</p>

			<form method="post" action="#newsletter">
				<xsl:choose>
					<xsl:when test="$event/@result = 'success'">
						<p class="{$event/@result}">Successfully subscribed.</p>
					</xsl:when>
					<xsl:when test="$event/@result = 'error'">
						<p class="{$event/@result}">
							<xsl:value-of select="$event/error"/>
						</p>
					</xsl:when>
				</xsl:choose>

				<fieldset>
					<input type="hidden" name="merge[FNAME]" value="John"/>
					<input type="hidden" name="merge[LNAME]" value="Doe"/>
					<input type="hidden" name="action[signup]" value="yes"/>

					<button type="submit">Subscribe</button>

					<input type="text" class="required email" name="email" value="{$event/cookies/cookie[@handle = 'email']}" placeholder="email"/>
				</fieldset>
			</form>
		</div>
	</xsl:template>




</xsl:stylesheet>

<xsl:stylesheet version='1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform' >
  <xsl:output method="html" encoding="utf-8" />
  <xsl:template match="/">
    <xsl:call-template name="head"/>
    <xsl:apply-templates select="/grab" />
    <xsl:call-template name="foot"/>
  </xsl:template>

  <xsl:template match="/grab">
    <xsl:element name="div">
      <table class="Info" width="450pt">
        <tr>
            <td class="Key">programme</td>
            <td class="Val"><xsl:value-of select="./tel_name"/></td>
        </tr>
        <tr>
            <td class="Key">series</td>
            <td class="Val"><xsl:value-of select="./tel_series"/></td>
        </tr>
        <tr>
            <td class="Key">episode</td>
            <td class="Val"><xsl:value-of select="./tel_episode"/></td>
        </tr>
        <tr>
            <td class="Key">part</td>
            <td class="Val"><xsl:value-of select="./tel_part"/></td>
        </tr>
        <tr>
            <td class="Key">channel</td>
            <td class="Val"><xsl:value-of select="./channel_name"/></td>
        </tr>
        <tr>
            <td class="Key">record name</td>
            <td class="Val"><xsl:value-of select="./grb_name"/></td>
        </tr>
        <tr>
            <td class="Key">programme start</td>
            <td class="Val"><xsl:value-of select="./tel_date_start"/> (<xsl:value-of select="./tel_date_start_timestamp"/>)</td>
        </tr>
        <tr>
            <td class="Key">programme end</td>
            <td class="Val"><xsl:value-of select="./tel_date_end"/> (<xsl:value-of select="./tel_date_end_timestamp"/>)</td>
        </tr>
        <tr>
            <td class="Key">recording start</td>
            <td class="Val"><xsl:value-of select="./grb_date_start"/> (<xsl:value-of select="./grb_date_start_timestamp"/>)</td>
        </tr>
        <tr>
            <td class="Key">recording end</td>
            <td class="Val"><xsl:value-of select="./grb_date_end"/> (<xsl:value-of select="./grb_date_end_timestamp"/>)</td>
        </tr>
        <tr>
            <td class="Key">encoded: <xsl:value-of select="./enc_codec"/><br />
                            state:   <xsl:value-of select="./req_status"/>
            </td>
            <td class="Val">file: <xsl:value-of select="./req_output"/><br />
                            size: <xsl:value-of select="./req_output_size div 1024"/>MB (<xsl:value-of select="./req_output_size"/>kB)<br />
                            md5: <xsl:value-of select="./req_output_md5"/><br />
            </td>
        </tr>
      </table>
    </xsl:element>
  </xsl:template>
  <xsl:template name="head">
    <xsl:text disable-output-escaping="yes">
    <![CDATA[
      <html>
      <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta>
        <title>DVBgrab info</title>
      </head>
      <style type="text/css">
      body {
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 1em;
        background-color : #000000;
        color : #999999;
      }
      .Info {
        border: 1px solid ;
        background-color: #99f699;
        white-space: nowrap;
      }
      .Key {
        color: #00834f;
      }
      .Val {
        color: #111111;
      }
      </style>
      <body>
     ]]>
    </xsl:text>
  </xsl:template>

  <xsl:template name="foot">
    <xsl:text disable-output-escaping="yes">
      <![CDATA[
      </body>
      </html>
      ]]>
    </xsl:text>
  </xsl:template>
</xsl:stylesheet>
